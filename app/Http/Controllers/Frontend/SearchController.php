<?php
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
