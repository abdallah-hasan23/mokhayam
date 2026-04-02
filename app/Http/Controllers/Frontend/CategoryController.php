<?php
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
