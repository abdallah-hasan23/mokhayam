<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\{HomeController, ArticleController, CategoryController, SearchController, PageController};
use App\Http\Controllers\Frontend\ContactController as FrontendContact;
use App\Http\Controllers\Dashboard\{
    HomeController as DashHome,
    ArticleController as DashArticle,
    WriterController,
    ContactController as DashContact,
    CategoryController as DashCategory,
    AnalyticsController,
    SettingsController,
    NotificationController,
    ProfileController,
};

// ── Frontend ──────────────────────────────────────────────────
Route::get('/',                [HomeController::class,'index'])->name('home');
Route::get('/article/{slug}',  [ArticleController::class,'show'])->name('article.show');
Route::get('/category/{slug}', [CategoryController::class,'show'])->name('category.show');
Route::get('/search',          [SearchController::class,'index'])->name('search');
Route::get('/about',           [PageController::class,'about'])->name('about');
Route::get('/contact',         [FrontendContact::class,'index'])->name('contact');
Route::post('/contact',        [FrontendContact::class,'store'])->name('contact.store');

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
    Route::get('/articles',                                [DashArticle::class,'index'])->name('articles.index');
    Route::get('/articles/create',                         [DashArticle::class,'create'])->name('articles.create');
    Route::post('/articles',                               [DashArticle::class,'store'])->name('articles.store');
    Route::get('/articles/{article}/edit',                 [DashArticle::class,'edit'])->name('articles.edit');
    Route::patch('/articles/{article}',                    [DashArticle::class,'update'])->name('articles.update');
    Route::delete('/articles/{article}',                   [DashArticle::class,'destroy'])->name('articles.destroy');
    Route::patch('/articles/{article}/publish',            [DashArticle::class,'publish'])->middleware('role:admin')->name('articles.publish');
    Route::patch('/articles/{article}/reject',             [DashArticle::class,'reject'])->middleware('role:admin')->name('articles.reject');

    // Article versioning
    Route::get('/articles/{article}/version/create',       [DashArticle::class,'versionCreate'])->name('articles.version.create');
    Route::post('/articles/{article}/version',             [DashArticle::class,'versionStore'])->name('articles.version.store');
    Route::get('/articles/{article}/versions',             [DashArticle::class,'versions'])->middleware('role:admin')->name('articles.versions');
    Route::patch('/versions/{version}/approve',            [DashArticle::class,'approveVersion'])->middleware('role:admin')->name('versions.approve');
    Route::patch('/versions/{version}/reject',             [DashArticle::class,'rejectVersion'])->middleware('role:admin')->name('versions.reject');

    // Writers (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/writers',           [WriterController::class,'index'])->name('writers.index');
        Route::post('/writers',          [WriterController::class,'store'])->name('writers.store');
        Route::patch('/writers/{user}',  [WriterController::class,'update'])->name('writers.update');
        Route::delete('/writers/{user}', [WriterController::class,'destroy'])->name('writers.destroy');
    });

    // Contact messages (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/contact',                       [DashContact::class,'index'])->name('contact.index');
        Route::get('/contact/{message}',             [DashContact::class,'show'])->name('contact.show');
        Route::delete('/contact/{message}',          [DashContact::class,'destroy'])->name('contact.destroy');
        Route::post('/contact/mark-all-read',        [DashContact::class,'markAllRead'])->name('contact.markAllRead');
    });

    // Categories (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/categories',              [DashCategory::class,'index'])->name('categories.index');
        Route::post('/categories',             [DashCategory::class,'store'])->name('categories.store');
        Route::patch('/categories/{category}', [DashCategory::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[DashCategory::class,'destroy'])->name('categories.destroy');
    });

    // Settings (admin only)
    Route::middleware('role:admin')->group(function() {
        Route::get('/settings',  [SettingsController::class,'index'])->name('settings');
        Route::post('/settings', [SettingsController::class,'update'])->name('settings.update');
    });

    // Notifications
    Route::get('/notifications',                    [NotificationController::class,'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read',     [NotificationController::class,'markAllRead'])->name('notifications.markAllRead');
    Route::patch('/notifications/{id}/read',        [NotificationController::class,'markRead'])->name('notifications.read');
    Route::delete('/notifications',                 [NotificationController::class,'destroyAll'])->name('notifications.destroyAll');

    // Profile
    Route::get('/profile',            [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',          [ProfileController::class,'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class,'updatePassword'])->name('profile.password');
});
