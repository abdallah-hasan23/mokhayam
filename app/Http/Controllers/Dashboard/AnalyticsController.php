<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category,Subscriber};
use Illuminate\Http\Request;

class AnalyticsController extends Controller {
    public function index(Request $request) {
        $period = $request->get('period','month');
        $from   = match($period){'week'=>now()->subDays(7),'year'=>now()->subDays(365),default=>now()->subDays(30)};
        $stats  = [
            'total_views'  => Article::published()->sum('views'),
            'period_views' => Article::published()->where('updated_at','>=',$from)->sum('views'),
            'total_subs'   => Subscriber::active()->count(),
            'open_rate'    => '٤٢٪',
        ];
        $topArticles   = Article::with(['category','user'])->published()->orderByDesc('views')->limit(10)->get();
        $categoryStats = Category::withCount('publishedArticles')->withSum(['articles as total_views'],'views')->orderByDesc('total_views')->get();
        $chartData     = collect(range(29,0))->map(fn($d) => ['date'=>now()->subDays($d)->format('d/m'),'views'=>rand(500,3000)]);
        return view('dashboard.analytics', compact('stats','topArticles','categoryStats','chartData','period'));
    }
}
