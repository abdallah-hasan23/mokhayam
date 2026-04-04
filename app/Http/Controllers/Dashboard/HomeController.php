<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleVersion;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\User;

class HomeController extends Controller {
    public function index() {
        $stats = [
            'total_articles'    => Article::count(),
            'published'         => Article::where('status','published')->count(),
            'pending_articles'  => Article::where('status','pending')->count(),
            'pending_versions'  => ArticleVersion::where('status','pending')->count(),
            'total_views'       => Article::sum('views'),
            'views_this_month'  => Article::whereMonth('updated_at',now()->month)->sum('views'),
            'unread_contact'    => ContactMessage::where('is_read',false)->count(),
            'active_users'      => User::where('is_active',true)->count(),
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
