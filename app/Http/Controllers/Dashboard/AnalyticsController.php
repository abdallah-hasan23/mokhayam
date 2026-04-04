<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller {
    public function index(Request $request) {
        $period = $request->get('period','month');
        $days   = match($period){'week'=>7,'year'=>365,default=>30};
        $from   = now()->subDays($days)->startOfDay();

        $stats = [
            'total_views'    => Article::published()->sum('views'),
            'total_articles' => Article::published()->count(),
            'period_articles'=> Article::published()->where('published_at','>=',$from)->count(),
            'pending_count'  => Article::where('status','pending')->count(),
        ];

        $topArticles   = Article::with(['category','user'])->published()->orderByDesc('views')->limit(10)->get();
        $categoryStats = Category::withCount('publishedArticles')
            ->withSum(['publishedArticles as total_views'], 'views')
            ->orderByDesc('total_views')
            ->get();

        // Real chart: articles published per day for the chosen period
        $publishedByDay = Article::published()
            ->where('published_at', '>=', $from)
            ->select(DB::raw('DATE(published_at) as pub_date'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('pub_date')
            ->pluck('cnt', 'pub_date');

        $chartData = collect(range($days - 1, 0))->map(function($d) use ($publishedByDay) {
            $date = now()->subDays($d);
            return [
                'date'  => $date->format('d/m'),
                'views' => $publishedByDay[$date->toDateString()] ?? 0,
            ];
        });

        return view('dashboard.analytics', compact('stats','topArticles','categoryStats','chartData','period'));
    }
}
