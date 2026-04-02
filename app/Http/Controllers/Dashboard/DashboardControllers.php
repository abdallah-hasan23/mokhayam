<?php
// ============================================================
// app/Http/Controllers/Dashboard/HomeController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category,Comment,Subscriber};

class HomeController extends Controller {
    public function index() {
        $stats = [
            'total_articles'   => Article::count(),
            'published'        => Article::where('status','published')->count(),
            'pending_review'   => Article::where('status','review')->count(),
            'total_views'      => Article::sum('views'),
            'views_this_month' => Article::whereMonth('updated_at',now()->month)->sum('views'),
            'total_subs'       => Subscriber::active()->count(),
            'new_subs'         => Subscriber::active()->thisMonth()->count(),
            'pending_comments' => Comment::pending()->count(),
        ];
        $chartData = collect(range(29,0))->map(fn($d) => [
            'date'  => now()->subDays($d)->format('d/m'),
            'views' => Article::whereDate('updated_at', now()->subDays($d))->sum('views'),
        ]);
        $topArticles    = Article::with(['user','category'])->published()->orderByDesc('views')->limit(5)->get();
        $categories     = Category::withCount(['articles'=>fn($q)=>$q->published()])->orderByDesc('articles_count')->get();
        $recentActivity = Article::with(['user','category'])->orderByDesc('updated_at')->limit(6)->get();
        return view('dashboard.home', compact('stats','chartData','topArticles','categories','recentActivity'));
    }
}

// ============================================================
// app/Http/Controllers/Dashboard/ArticleController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category,Tag,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller {
    public function index(Request $request) {
        $query = Article::with(['user','category'])->orderByDesc('created_at');
        if ($request->filled('status') && $request->status !== 'all') $query->where('status',$request->status);
        if ($request->filled('search')) $query->where('title','like','%'.$request->search.'%');
        $articles   = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $counts = [
            'all'       => Article::count(),
            'published' => Article::where('status','published')->count(),
            'draft'     => Article::where('status','draft')->count(),
            'review'    => Article::where('status','review')->count(),
            'rejected'  => Article::where('status','rejected')->count(),
        ];
        return view('dashboard.articles.index', compact('articles','categories','counts'));
    }

    public function create() {
        return view('dashboard.articles.create', [
            'categories' => Category::orderBy('order')->get(),
            'writers'    => User::where('is_active',true)->orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'category_id'=> 'required|exists:categories,id',
            'user_id'    => 'required|exists:users,id',
            'status'     => 'required|in:draft,review,published',
            'featured_image' => 'nullable|image|max:5120',
        ]);
        $data = $request->except(['featured_image','tags','_token']);
        $data['slug'] = Str::slug($request->title);
        if ($request->hasFile('featured_image'))
            $data['featured_image'] = $request->file('featured_image')->store('articles','public');
        if ($request->status === 'published') $data['published_at'] = now();
        $article = Article::create($data);
        if ($request->filled('tags')) {
            $ids = collect(explode(',',$request->tags))->filter()->map(
                fn($t) => Tag::firstOrCreate(['slug'=>Str::slug(trim($t))],['name'=>trim($t)])->id
            );
            $article->tags()->sync($ids);
        }
        return redirect()->route('dashboard.articles.index')->with('success','تم حفظ المقال بنجاح');
    }

    public function edit(Article $article) {
        return view('dashboard.articles.edit', [
            'article'    => $article->load('tags'),
            'categories' => Category::orderBy('order')->get(),
            'writers'    => User::where('is_active',true)->orderBy('name')->get(),
            'articleTags'=> $article->tags->pluck('name')->implode(', '),
        ]);
    }

    public function update(Request $request, Article $article) {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'category_id'=> 'required|exists:categories,id',
            'user_id'    => 'required|exists:users,id',
            'status'     => 'required|in:draft,review,published,rejected',
            'featured_image' => 'nullable|image|max:5120',
        ]);
        $data = $request->except(['featured_image','tags','_token','_method']);
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) Storage::disk('public')->delete($article->featured_image);
            $data['featured_image'] = $request->file('featured_image')->store('articles','public');
        }
        if ($request->status === 'published' && !$article->published_at) $data['published_at'] = now();
        $article->update($data);
        if ($request->has('tags')) {
            $ids = collect(explode(',',$request->tags))->filter()->map(
                fn($t) => Tag::firstOrCreate(['slug'=>Str::slug(trim($t))],['name'=>trim($t)])->id
            );
            $article->tags()->sync($ids);
        }
        return redirect()->route('dashboard.articles.index')->with('success','تم تحديث المقال');
    }

    public function destroy(Article $article) {
        if ($article->featured_image) Storage::disk('public')->delete($article->featured_image);
        $article->delete();
        return redirect()->route('dashboard.articles.index')->with('success','تم حذف المقال');
    }

    public function publish(Article $article) {
        $article->update(['status'=>'published','published_at'=>now()]);
        return response()->json(['message'=>'تم النشر','status'=>'published']);
    }

    public function reject(Article $article) {
        $article->update(['status'=>'rejected']);
        return response()->json(['message'=>'تم الرفض','status'=>'rejected']);
    }
}

// ============================================================
// app/Http/Controllers/Dashboard/WriterController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WriterController extends Controller {
    public function index() {
        return view('dashboard.writers.index', [
            'writers' => User::withCount('articles')->orderByDesc('articles_count')->get()
        ]);
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','role'=>'required|in:admin,editor,writer','password'=>'required|min:8']);
        User::create(['name'=>$request->name,'email'=>$request->email,'role'=>$request->role,'job_title'=>$request->job_title,'password'=>Hash::make($request->password)]);
        return redirect()->route('dashboard.writers.index')->with('success','تم إضافة الكاتب');
    }
    public function update(Request $request, User $user) {
        $request->validate(['name'=>'required','role'=>'required|in:admin,editor,writer']);
        $user->update($request->only(['name','role','job_title','bio','is_active']));
        return redirect()->route('dashboard.writers.index')->with('success','تم التحديث');
    }
    public function destroy(User $user) {
        if ($user->id === auth()->id()) return back()->with('error','لا يمكنك حذف حسابك');
        $user->delete();
        return redirect()->route('dashboard.writers.index')->with('success','تم الحذف');
    }
}

// ============================================================
// app/Http/Controllers/Dashboard/CommentController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller {
    public function index(Request $request) {
        $query = Comment::with('article')->orderByDesc('created_at');
        if ($request->filled('status') && $request->status !== 'all') $query->where('status',$request->status);
        $comments = $query->paginate(20)->withQueryString();
        $counts = ['all'=>Comment::count(),'pending'=>Comment::where('status','pending')->count(),'approved'=>Comment::where('status','approved')->count(),'rejected'=>Comment::where('status','rejected')->count()];
        return view('dashboard.comments.index', compact('comments','counts'));
    }
    public function approve(Comment $comment) { $comment->update(['status'=>'approved']); return back()->with('success','تمت الموافقة'); }
    public function reject(Comment $comment)  { $comment->update(['status'=>'rejected']); return back()->with('success','تم الرفض'); }
    public function destroy(Comment $comment) { $comment->delete(); return back()->with('success','تم الحذف'); }
    public function approveAll() { Comment::pending()->update(['status'=>'approved']); return back()->with('success','تمت الموافقة على الجميع'); }
}

// ============================================================
// app/Http/Controllers/Dashboard/SubscriberController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller {
    public function index(Request $request) {
        $query = Subscriber::orderByDesc('created_at');
        if ($request->filled('search')) $query->where('email','like','%'.$request->search.'%');
        $subscribers = $query->paginate(25)->withQueryString();
        $stats = ['total'=>Subscriber::active()->count(),'this_month'=>Subscriber::active()->thisMonth()->count()];
        return view('dashboard.subscribers.index', compact('subscribers','stats'));
    }
    public function destroy(Subscriber $subscriber) { $subscriber->delete(); return back()->with('success','تم الحذف'); }
    public function export() {
        $headers = ['Content-Type'=>'text/csv; charset=UTF-8','Content-Disposition'=>'attachment; filename="subscribers.csv"'];
        $subs    = Subscriber::active()->get();
        return response()->stream(function() use($subs) {
            $f = fopen('php://output','w');
            fputs($f,"\xEF\xBB\xBF");
            fputcsv($f,['البريد','تاريخ الاشتراك','المصدر','الحالة']);
            foreach($subs as $s) fputcsv($f,[$s->email,$s->created_at->format('Y-m-d'),$s->source,$s->is_active?'نشط':'غير نشط']);
            fclose($f);
        },200,$headers);
    }
}

// ============================================================
// app/Http/Controllers/Dashboard/CategoryController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function index() {
        return view('dashboard.categories.index', [
            'categories' => Category::withCount(['articles','publishedArticles'])->orderBy('order')->get()
        ]);
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required|string|max:100']);
        Category::create($request->only(['name','description','color','order']));
        return redirect()->route('dashboard.categories.index')->with('success','تم إنشاء القسم');
    }
    public function update(Request $request, Category $category) {
        $request->validate(['name'=>'required|string|max:100']);
        $category->update($request->only(['name','description','color','order']));
        return redirect()->route('dashboard.categories.index')->with('success','تم التحديث');
    }
    public function destroy(Category $category) {
        if ($category->articles()->exists()) return back()->with('error','لا يمكن حذف قسم يحتوي على مقالات');
        $category->delete();
        return redirect()->route('dashboard.categories.index')->with('success','تم الحذف');
    }
}

// ============================================================
// app/Http/Controllers/Dashboard/AnalyticsController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category,Subscriber};
use Illuminate\Http\Request;

class AnalyticsController extends Controller {
    public function index(Request $request) {
        $period = $request->get('period','month');
        $from   = match($period){'week'=>now()->subDays(7),'year'=>now()->subDays(365),default=>now()->subDays(30)};
        $stats  = [
            'total_views'  => Article::published()->sum('views'),
            'period_views' => Article::published()->where('updated_at','>=',$from)->sum('views'),
            'total_subs'   => Subscriber::active()->count(),
            'open_rate'    => '٤٢٪',
        ];
        $topArticles   = Article::with(['category','user'])->published()->orderByDesc('views')->limit(10)->get();
        $categoryStats = Category::withCount('publishedArticles')->withSum(['articles as total_views'],'views')->orderByDesc('total_views')->get();
        $chartData     = collect(range(29,0))->map(fn($d) => ['date'=>now()->subDays($d)->format('d/m'),'views'=>rand(500,3000)]);
        return view('dashboard.analytics', compact('stats','topArticles','categoryStats','chartData','period'));
    }
}

// ============================================================
// app/Http/Controllers/Dashboard/SettingsController.php
// ============================================================
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller {
    public function index() {
        $settingsFile = storage_path('app/settings.json');
        $settings = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];
        $defaults = ['site_name'=>'مخيّم','site_tagline'=>'رواية الإنسان في زمن الحرب','site_email'=>'editor@mukhayyam.ps','articles_per_page'=>8,'comments_auto'=>false,'telegram'=>'','twitter'=>'','instagram'=>'','youtube'=>''];
        $settings = array_merge($defaults, $settings);
        return view('dashboard.settings', compact('settings'));
    }
    public function update(Request $request) {
        $data = $request->only(['site_name','site_tagline','site_email','articles_per_page','comments_auto','telegram','twitter','instagram','youtube']);
        file_put_contents(storage_path('app/settings.json'), json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        return back()->with('success','تم حفظ الإعدادات');
    }
}
