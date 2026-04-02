<?php
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
