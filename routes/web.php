<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\{HomeController,ArticleController,CategoryController,SearchController,SubscribeController,PageController};
use App\Http\Controllers\Dashboard\{HomeController as DashHome, ArticleController as DashArticle, WriterController, CommentController, SubscriberController, CategoryController as DashCategory, AnalyticsController, SettingsController};

// ── Frontend ──────────────────────────────────────────────────
Route::get('/',                          [HomeController::class,'index'])->name('home');
Route::get('/article/{slug}',            [ArticleController::class,'show'])->name('article.show');
Route::post('/article/{slug}/comment',   [ArticleController::class,'storeComment'])->name('article.comment');
Route::get('/category/{slug}',           [CategoryController::class,'show'])->name('category.show');
Route::get('/search',                    [SearchController::class,'index'])->name('search');
Route::post('/subscribe',                [SubscribeController::class,'store'])->name('subscribe');
Route::get('/unsubscribe/{token}',       [SubscribeController::class,'unsubscribe'])->name('unsubscribe');
Route::get('/about',                     [PageController::class,'about'])->name('about');

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function() {
    Route::get('/login',  [LoginController::class,'showForm'])->name('login');
    Route::post('/login', [LoginController::class,'login'])->name('login.post');
});
Route::post('/logout', [LoginController::class,'logout'])->middleware('auth')->name('logout');

// ── Dashboard ─────────────────────────────────────────────────
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth','active'])->group(function() {

    Route::get('/',           [DashHome::class,'index'])->name('home');
    Route::get('/analytics',  [AnalyticsController::class,'index'])->middleware('role:admin,editor')->name('analytics');

    // Articles
    Route::resource('articles', DashArticle::class)->except(['show']);
    Route::patch('/articles/{article}/publish', [DashArticle::class,'publish'])->middleware('role:admin,editor')->name('articles.publish');
    Route::patch('/articles/{article}/reject',  [DashArticle::class,'reject'])->middleware('role:admin,editor')->name('articles.reject');

    // Writers (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/writers',              [WriterController::class,'index'])->name('writers.index');
        Route::post('/writers',             [WriterController::class,'store'])->name('writers.store');
        Route::patch('/writers/{user}',     [WriterController::class,'update'])->name('writers.update');
        Route::delete('/writers/{user}',    [WriterController::class,'destroy'])->name('writers.destroy');
    });

    // Comments
    Route::get('/comments',                       [CommentController::class,'index'])->name('comments.index');
    Route::patch('/comments/{comment}/approve',   [CommentController::class,'approve'])->name('comments.approve');
    Route::patch('/comments/{comment}/reject',    [CommentController::class,'reject'])->name('comments.reject');
    Route::delete('/comments/{comment}',          [CommentController::class,'destroy'])->name('comments.destroy');
    Route::post('/comments/approve-all',          [CommentController::class,'approveAll'])->name('comments.approveAll');

    // Subscribers (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/subscribers',           [SubscriberController::class,'index'])->name('subscribers.index');
        Route::delete('/subscribers/{sub}',  [SubscriberController::class,'destroy'])->name('subscribers.destroy');
        Route::get('/subscribers/export',    [SubscriberController::class,'export'])->name('subscribers.export');
    });

    // Categories (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/categories',            [DashCategory::class,'index'])->name('categories.index');
        Route::post('/categories',           [DashCategory::class,'store'])->name('categories.store');
        Route::patch('/categories/{category}',[DashCategory::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[DashCategory::class,'destroy'])->name('categories.destroy');
    });

    // Settings (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/settings',  [SettingsController::class,'index'])->name('settings');
        Route::post('/settings', [SettingsController::class,'update'])->name('settings.update');
    });
});
