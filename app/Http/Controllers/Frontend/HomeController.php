<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\{Article,Category,User,Setting,Issue};

class HomeController extends Controller {
    public function index() {
        // Hero section — مقالات من التصنيفات المرئية فقط (show_in_nav = true)
        $heroArticles    = Article::with(['user','category'])
                            ->published()
                            ->whereHas('category', fn($q) => $q->where('show_in_nav', true))
                            ->latest('published_at')
                            ->limit(5)->get();

        // أحدث المقالات — نفس الفلتر، مع تخطي أول 5 مقالات الظاهرة في الـ hero
        $latestArticles  = Article::with(['user','category'])
                            ->published()
                            ->whereHas('category', fn($q) => $q->where('show_in_nav', true))
                            ->latest('published_at')
                            ->skip(5)->limit(6)->get();

        // القراءة المعمّقة — من التصنيفات المرئية أيضاً
        $longRead        = Article::with(['user','category'])
                            ->published()
                            ->whereHas('category', fn($q) => $q->where('show_in_nav', true))
                            ->orderByDesc('views')
                            ->skip(2)->first();

        // التصنيف المميّز في الشريط الجانبي — من المرئية فقط
        $featuredCat     = Category::visible()->orderBy('order')->skip(1)->first();
        $featuredArticles= $featuredCat
                            ? Article::with(['user','category'])
                                ->published()
                                ->where('category_id', $featuredCat->id)
                                ->latest('published_at')
                                ->limit(3)->get()
                            : collect();

        // سحابة التصنيفات في الـ sidebar — المرئية فقط
        $categories      = Category::visible()->withCount('publishedArticles')->orderBy('order')->get();

        // الأكثر قراءة — من التصنيفات المرئية
        $mostRead        = Article::with(['user','category'])
                            ->published()
                            ->whereHas('category', fn($q) => $q->where('show_in_nav', true))
                            ->orderByDesc('views')
                            ->limit(5)->get();

        // Hero stats
        $heroTitle       = Setting::get('about_hero_title')    ?: 'نرى ما لا تراه الكاميرات';
        $heroSubtitle    = Setting::get('about_hero_subtitle') ?: 'مخيّم منصة صحفية عربية مستقلة تُعنى بالقصة الإنسانية خلف الحرب والنزوح.';
        $totalArticles   = Article::published()->count();
        $totalWriters    = User::where('is_active', true)->count();
        $totalCategories = Category::count();
        $ctaTitle        = Setting::get('about_cta_title') ?: 'أرسل قصتك';
        $ctaText         = Setting::get('about_cta_text')  ?: 'هل لديك قصة تستحق أن تُروى؟ باب مخيّم مفتوح لكل من عاش لحظة تستحق الشهادة.';
        $latestIssue     = Issue::published()->orderByDesc('issue_number')->first();

        return view('frontend.home', compact(
            'heroArticles','latestArticles','longRead','featuredArticles','featuredCat',
            'categories','mostRead','heroTitle','heroSubtitle',
            'totalArticles','totalWriters','totalCategories','ctaTitle','ctaText',
            'latestIssue'
        ));
    }
}
