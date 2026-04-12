<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleVersion;
use App\Models\Category;
use App\Models\User;
use App\Notifications\ArticleSubmitted;
use App\Notifications\ArticleStatusChanged;
use App\Notifications\NewVersionSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Article::with(['user','category']);

        // Writers/editors only see their own articles
        if ($user->isWriter() || (!$user->isAdmin() && $user->role === 'editor')) {
            $query->where('user_id', $user->id);
        }

        // Status filter
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Internal dashboard search
        if ($request->q) {
            $q = $request->q;
            $query->where(function($sq) use ($q) {
                $sq->where('title','like',"%{$q}%")
                   ->orWhere('content','like',"%{$q}%")
                   ->orWhereHas('user', fn($u) => $u->where('name','like',"%{$q}%"));
            });
        }

        $articles = $query->latest()->paginate(15)->withQueryString();

        // Counts (scoped to visible articles)
        $countQuery = Article::query();
        if ($user->isWriter() || (!$user->isAdmin() && $user->role === 'editor')) {
            $countQuery->where('user_id', $user->id);
        }
        $counts = [
            'all'       => (clone $countQuery)->count(),
            'draft'     => (clone $countQuery)->where('status','draft')->count(),
            'pending'   => (clone $countQuery)->where('status','pending')->count(),
            'published' => (clone $countQuery)->where('status','published')->count(),
            'rejected'  => (clone $countQuery)->where('status','rejected')->count(),
        ];

        return view('dashboard.articles.index', compact('articles','counts'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();
        $writers    = User::where('is_active', true)->orderBy('name')->get();
        return view('dashboard.articles.create', compact('categories','writers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'title'       => 'required|string|max:500',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'excerpt'     => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|max:3072',
            'meta_title'  => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
        ]);

        $data = $request->only(['title','content','category_id','excerpt','meta_title','meta_description']);
        $data['user_id'] = $user->isAdmin() ? ($request->user_id ?? $user->id) : $user->id;

        // Determine status
        if ($user->isAdmin() && $request->status) {
            $data['status'] = $request->status;
            if ($request->status === 'published') {
                $data['published_at'] = now();
            }
        } else {
            $data['status'] = 'pending'; // Always submit for approval
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $article = Article::create($data);

        // Notify all admins (only if pending)
        if ($data['status'] === 'pending') {
            $this->notifyAdmins(new ArticleSubmitted($article));
        }

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'تم إرسال المقال بنجاح وهو بانتظار الموافقة');
    }

    public function edit(Article $article)
    {
        $user = Auth::user();

        // Check: published article → redirect to version creation
        if ($article->status === 'published' && !$user->isAdmin()) {
            return redirect()->route('dashboard.articles.version.create', $article->id);
        }

        // Check: pending article → author/editor cannot edit
        if ($article->status === 'pending' && !$user->isAdmin()) {
            return redirect()->route('dashboard.articles.index')
                ->with('info', 'لا يمكن تعديل المقال أثناء انتظار الموافقة');
        }

        // Check ownership for non-admins
        if (!$user->isAdmin() && $article->user_id !== $user->id) {
            abort(403);
        }

        $categories = Category::orderBy('order')->get();
        $writers    = User::where('is_active', true)->orderBy('name')->get();
        return view('dashboard.articles.edit', compact('article','categories','writers'));
    }

    public function update(Request $request, Article $article)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $article->user_id !== $user->id) abort(403);
        if ($article->status === 'pending' && !$user->isAdmin()) {
            return back()->with('error', 'لا يمكن تعديل المقال أثناء انتظار الموافقة');
        }

        $request->validate([
            'title'       => 'required|string|max:500',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'excerpt'     => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|max:3072',
        ]);

        $data = $request->only(['title','content','category_id','excerpt','meta_title','meta_description']);

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) Storage::disk('public')->delete($article->featured_image);
            $data['featured_image'] = $request->file('featured_image')->store('articles','public');
        } elseif ($request->input('clear_featured_image') == '1' && $article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
            $data['featured_image'] = null;
        }

        // If admin is saving → can set status directly
        if ($user->isAdmin() && $request->status) {
            $data['status'] = $request->status;
            if ($request->status === 'published' && !$article->published_at) {
                $data['published_at'] = now();
            }
        } else {
            // Non-admin editing draft/rejected → keep status as pending on save
            $data['status'] = 'pending';
        }

        $article->update($data);

        if (!$user->isAdmin()) {
            $this->notifyAdmins(new ArticleSubmitted($article));
        }

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'تم حفظ المقال بنجاح');
    }

    /** Show form to create a new version of a published article */
    public function versionCreate(Article $article)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $article->user_id !== $user->id) abort(403);

        $categories = Category::orderBy('order')->get();
        return view('dashboard.articles.version_create', compact('article','categories'));
    }

    /** Store a new pending version */
    public function versionStore(Request $request, Article $article)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $article->user_id !== $user->id) abort(403);

        $request->validate([
            'title'   => 'required|string|max:500',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|max:3072',
        ]);

        $data = $request->only(['title','content','excerpt','meta_title','meta_description']);
        $data['article_id']   = $article->id;
        $data['submitted_by'] = $user->id;
        $data['status']       = 'pending';

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('articles','public');
        }

        $version = ArticleVersion::create($data);

        // Notify admins
        $this->notifyAdmins(new NewVersionSubmitted($article, $version));

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'تم إرسال النسخة الجديدة بنجاح وهي بانتظار الموافقة');
    }

    /** Admin: show pending versions of an article */
    public function versions(Article $article)
    {
        $versions = $article->versions()->with('submitter')->get();
        return view('dashboard.articles.versions', compact('article','versions'));
    }

    /** Admin: approve a pending version → apply to main article */
    public function approveVersion(ArticleVersion $version)
    {
        $article = $version->article;

        $article->update([
            'title'             => $version->title,
            'excerpt'           => $version->excerpt,
            'content'           => $version->content,
            'featured_image'    => $version->featured_image ?? $article->featured_image,
            'meta_title'        => $version->meta_title,
            'meta_description'  => $version->meta_description,
        ]);

        $version->update(['status' => 'approved']);

        // Notify submitter
        $version->submitter->notify(new ArticleStatusChanged($article, 'published'));

        return back()->with('success', 'تمت الموافقة على النسخة وتطبيقها على المقال');
    }

    /** Admin: reject a pending version */
    public function rejectVersion(ArticleVersion $version)
    {
        $version->update(['status' => 'rejected']);
        $version->submitter->notify(new ArticleStatusChanged($version->article, 'rejected'));
        return back()->with('success', 'تم رفض النسخة');
    }

    public function publish(Article $article)
    {
        $article->update([
            'status'       => 'published',
            'published_at' => $article->published_at ?? now(),
        ]);

        // Notify author
        $article->user->notify(new ArticleStatusChanged($article, 'published'));

        if (request()->expectsJson()) return response()->json(['success' => true]);
        return back()->with('success', 'تم نشر المقال');
    }

    public function reject(Article $article)
    {
        $article->update(['status' => 'rejected']);
        $article->user->notify(new ArticleStatusChanged($article, 'rejected'));

        if (request()->expectsJson()) return response()->json(['success' => true]);
        return back()->with('success', 'تم رفض المقال');
    }

    public function destroy(Article $article)
    {
        if (!Auth::user()->isAdmin() && $article->user_id !== Auth::id()) abort(403);
        if ($article->featured_image) Storage::disk('public')->delete($article->featured_image);
        $article->delete();
        return back()->with('success', 'تم حذف المقال');
    }

    private function notifyAdmins($notification): void
    {
        User::where('role','admin')->each(fn($admin) => $admin->notify($notification));
    }
}
