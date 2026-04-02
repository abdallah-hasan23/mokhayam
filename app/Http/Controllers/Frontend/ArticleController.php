<?php
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
