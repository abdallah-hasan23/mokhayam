<?php
// ============================================================
// app/Http/Controllers/Frontend/HomeController.php
// ============================================================
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category};

class HomeController extends Controller {
    public function index() {
        $heroArticles    = Article::with(['user','category'])->published()->latest('published_at')->limit(5)->get();
        $latestArticles  = Article::with(['user','category'])->published()->latest('published_at')->skip(5)->limit(6)->get();
        $longRead        = Article::with(['user','category'])->published()->orderByDesc('views')->skip(2)->first();
        $featuredCat     = Category::orderBy('order')->skip(1)->first();
        $featuredArticles= $featuredCat ? Article::with(['user','category'])->published()->where('category_id',$featuredCat->id)->latest('published_at')->limit(3)->get() : collect();
        $categories      = Category::withCount('publishedArticles')->orderBy('order')->get();
        return view('frontend.home', compact('heroArticles','latestArticles','longRead','featuredArticles','featuredCat','categories'));
    }
}

// ============================================================
// app/Http/Controllers/Frontend/ArticleController.php
// ============================================================
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\{Article,Comment};
use Illuminate\Http\Request;

class ArticleController extends Controller {
    public function show(string $slug) {
        $article = Article::with(['user','category','tags','approvedComments'])->published()->where('slug',$slug)->firstOrFail();
        if (!session('viewed_'.$article->id)) { $article->incrementViews(); session(['viewed_'.$article->id=>true]); }
        $related = Article::with(['user','category'])->published()->where('category_id',$article->category_id)->where('id','!=',$article->id)->latest('published_at')->limit(3)->get();
        return view('frontend.article', compact('article','related'));
    }
    public function storeComment(Request $request, string $slug) {
        $article = Article::published()->where('slug',$slug)->firstOrFail();
        $request->validate(['author_name'=>'required|string|max:100','author_email'=>'required|email','body'=>'required|string|min:10|max:1000']);
        Comment::create(['article_id'=>$article->id,'author_name'=>$request->author_name,'author_email'=>$request->author_email,'body'=>$request->body,'status'=>'pending']);
        return back()->with('comment_sent','تم إرسال تعليقك وسيظهر بعد المراجعة ✓');
    }
}

// ============================================================
// app/Http/Controllers/Frontend/CategoryController.php
// ============================================================
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category};

class CategoryController extends Controller {
    public function show(string $slug) {
        $category  = Category::where('slug',$slug)->firstOrFail();
        $featured  = Article::with(['user','category'])->published()->where('category_id',$category->id)->latest('published_at')->limit(4)->get();
        $articles  = Article::with(['user','category'])->published()->where('category_id',$category->id)->latest('published_at')->skip(4)->paginate(8);
        $categories= Category::withCount('publishedArticles')->orderBy('order')->get();
        return view('frontend.category', compact('category','featured','articles','categories'));
    }
}

// ============================================================
// app/Http/Controllers/Frontend/SearchController.php
// ============================================================
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller {
    public function index(Request $request) {
        $q = $request->get('q');
        $articles = $q && strlen($q) >= 2
            ? Article::with(['user','category'])->published()
                ->where(fn($query) => $query->where('title','like',"%$q%")->orWhere('excerpt','like',"%$q%"))
                ->latest('published_at')->paginate(12)->withQueryString()
            : collect();
        return view('frontend.search', compact('articles','q'));
    }
}

// ============================================================
// app/Http/Controllers/Frontend/SubscribeController.php
// ============================================================
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribeController extends Controller {
    public function store(Request $request) {
        $request->validate(['email'=>'required|email']);
        Subscriber::firstOrCreate(['email'=>$request->email],['source'=>'website','is_active'=>true]);
        if ($request->expectsJson()) return response()->json(['message'=>'تم الاشتراك بنجاح!']);
        return back()->with('subscribed','شكراً! تم اشتراكك في النشرة البريدية.');
    }
    public function unsubscribe(string $token) {
        $sub = Subscriber::where('unsubscribe_token',$token)->firstOrFail();
        $sub->update(['is_active'=>false]);
        return view('frontend.unsubscribed');
    }
}

// ============================================================
// app/Http/Controllers/Frontend/PageController.php
// ============================================================
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class PageController extends Controller {
    public function about() { return view('frontend.about'); }
}
