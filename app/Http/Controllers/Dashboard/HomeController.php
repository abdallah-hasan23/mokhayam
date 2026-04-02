<?php
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
