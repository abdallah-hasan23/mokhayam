<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller {
    public function show(string $slug) {
        $article = Article::with(['user','category'])->published()->where('slug',$slug)->firstOrFail();
        if (!session('viewed_'.$article->id)) { $article->incrementViews(); session(['viewed_'.$article->id=>true]); }
        $related = Article::with(['user','category'])->published()->where('category_id',$article->category_id)->where('id','!=',$article->id)->latest('published_at')->limit(3)->get();
        return view('frontend.article', compact('article','related'));
    }
}
