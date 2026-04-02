<?php
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
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'user_id'     => 'required|exists:users,id',
            'status'      => 'required|in:draft,review,published',
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
            'article'     => $article->load('tags'),
            'categories'  => Category::orderBy('order')->get(),
            'writers'     => User::where('is_active',true)->orderBy('name')->get(),
            'articleTags' => $article->tags->pluck('name')->implode(', '),
        ]);
    }

    public function update(Request $request, Article $article) {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'user_id'     => 'required|exists:users,id',
            'status'      => 'required|in:draft,review,published,rejected',
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
