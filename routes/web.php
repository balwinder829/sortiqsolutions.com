<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\FresherHiringController as AdminFresherHiringController;
use App\Http\Controllers\Admin\InternshipApplicationController as AdminInternshipApplicationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageMetaController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SiteLayoutSettingController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\ClientController as FrontendClientController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\PortfolioController as FrontendPortfolioController;
use App\Http\Controllers\Frontend\ReviewController as FrontendReviewController;
use App\Http\Controllers\Frontend\VideoController as FrontendVideoController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

$frontendRoutes = config('frontend-routes');

$customPaths = [];
try {
    $customPathsSetting = \App\Models\SiteLayoutSetting::where('key', 'main')->first();
    $pageMeta = $customPathsSetting && is_array($customPathsSetting->data) 
        ? ($customPathsSetting->data['page_meta'] ?? []) 
        : [];
    foreach ($pageMeta as $routeName => $meta) {
        if (!empty($meta['custom_path'])) {
            $customPaths[$routeName] = $meta['custom_path'];
        }
    }
} catch (\Throwable $e) {
    // Ignore database errors during boot/migration/route caching
}

$registerViewRoutes = function (array $routes, string $namePrefix = 'frontend.', array $customPaths = []) : void {
    $draftRoutes = [];
    try {
        $setting = \App\Models\SiteLayoutSetting::where('key', 'main')->first();
        $pageMeta = ($setting && is_array($setting->data)) ? ($setting->data['page_meta'] ?? []) : [];
        foreach ($pageMeta as $rName => $m) {
            if (($m['status'] ?? 'published') === 'draft') {
                $draftRoutes[$rName] = true;
            }
        }
    } catch (\Throwable $e) {}

    foreach ($routes as $uri => $view) {
        $routeName = "{$namePrefix}{$uri}";
        $routePath = $customPaths[$routeName] ?? "/{$uri}";
        $routePath = '/' . ltrim($routePath, '/');
        if (isset($draftRoutes[$routeName])) {
            Route::get($routePath, function () { abort(404); })->name($routeName);
        } else {
            Route::view($routePath, $view)->name($routeName);
        }
    }
};

$registerSlugRoutes = function (array $slugs, string $viewPrefix, string $namePrefix, array $customPaths = []) : void {
    foreach ($slugs as $uri => $viewName) {
        if (is_int($uri)) {
             $uri = $viewName;
        }
        $routeName = "{$namePrefix}{$viewName}";
        $routePath = $customPaths[$routeName] ?? "/{$uri}";
        $routePath = '/' . ltrim($routePath, '/');
        Route::view($routePath, "{$viewPrefix}.{$viewName}")->name($routeName);
    }
};

$registerRedirects = function (array $redirects, string $targetPrefix = '') : void {
    foreach ($redirects as $old => $new) {
        Route::redirect("/{$old}", "{$targetPrefix}/{$new}", 301);
    }
};

$registerAdminResourceRoutes = function (string $prefix, string $name, string $controller, string $parameter) : void {
    Route::prefix($prefix)
        ->name("{$name}.")
        ->controller($controller)
        ->group(function () use ($parameter) {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get("/{{$parameter}}", 'show')->name('show');
            Route::get("/{{$parameter}}/edit", 'edit')->name('edit');
            Route::put("/{{$parameter}}", 'update')->name('update');
            Route::delete("/{{$parameter}}", 'destroy')->name('destroy');
        });
};

Route::get($customPaths['frontend.home'] ?? '/', [FrontendHomeController::class, 'index'])->name('frontend.home');
Route::get('/robots.txt', [\App\Http\Controllers\RobotsController::class, '__invoke'])->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::redirect('/sitemap_index.xml', '/sitemap.xml', 301);
Route::redirect('/sortiqsolution', '/', 301);

$staticPageRoutes = $frontendRoutes['pages'];
unset($staticPageRoutes['reviews'], $staticPageRoutes['clients'], $staticPageRoutes['videos'], $staticPageRoutes['portfolio']);

$registerViewRoutes($staticPageRoutes, 'frontend.', $customPaths);
$registerRedirects($frontendRoutes['legacy_pages']);

Route::get($customPaths['frontend.reviews'] ?? '/reviews', [FrontendReviewController::class, 'index'])->name('frontend.reviews');
Route::get('/reviews/{slug}', fn () => redirect()->route('frontend.reviews'))->name('frontend.reviews.show');
Route::get($customPaths['frontend.clients'] ?? '/clients', [FrontendClientController::class, 'index'])->name('frontend.clients');
Route::get(($customPaths['frontend.clients'] ?? '/clients') . '/page/{page}', [FrontendClientController::class, 'index'])
    ->whereNumber('page')
    ->name('frontend.clients.page');
Route::get($customPaths['frontend.videos'] ?? '/videos', [FrontendVideoController::class, 'index'])->name('frontend.videos');
Route::get($customPaths['frontend.portfolio'] ?? '/portfolios', [FrontendPortfolioController::class, 'index'])->name('frontend.portfolio');
Route::redirect('/portfolio', '/portfolios', 301);

if (isset($customPaths['frontend.blog.index'])) {
    Route::get($customPaths['frontend.blog.index'], [FrontendBlogController::class, 'index'])->name('frontend.blog.index');
    Route::prefix('blog')->name('frontend.blog.')->group(function () {
        Route::get('/feed.json', [FrontendBlogController::class, 'feed'])->name('feed');
        Route::get('/{slug}', [FrontendBlogController::class, 'show'])->name('show');
    });
} else {
    Route::prefix('blog')->name('frontend.blog.')->group(function () {
        Route::get('/', [FrontendBlogController::class, 'index'])->name('index');
        Route::get('/feed.json', [FrontendBlogController::class, 'feed'])->name('feed');
        Route::get('/{slug}', [FrontendBlogController::class, 'show'])->name('show');
    });
}

if (isset($customPaths['frontend.services.index'])) {
    Route::view($customPaths['frontend.services.index'], 'frontend.services.services-page')->name('frontend.services.index');
} else {
    Route::prefix('services')->name('frontend.services.')->group(function () {
        Route::view('/', 'frontend.services.services-page')->name('index');
    });
}

$registerSlugRoutes($frontendRoutes['services'], 'frontend.services', 'frontend.services.', $customPaths);
// Dynamic custom pages route handler (Draft pages return 404 on front-end)
try {
    $setting = \App\Models\SiteLayoutSetting::where('key', 'main')->first();
    $data = $setting && is_array($setting->data) ? $setting->data : [];
    $customPages = $data['custom_pages'] ?? [];
    $pageMeta = $data['page_meta'] ?? [];

    foreach ($customPages as $routeName => $page) {
        $slug = $page['slug'] ?? str_replace('frontend.', '', $routeName);
        $status = $pageMeta[$routeName]['status'] ?? ($page['status'] ?? 'published');

        Route::get('/' . ltrim($slug, '/'), function () use ($status) {
            if ($status === 'draft') {
                abort(404);
            }
            return view('frontend.home');
        })->name($routeName);
    }
} catch (\Throwable $e) {
    // Ignore database errors during boot
}

Route::redirect('/admin', '/sortiqadmin', 301);

Route::prefix('sortiqadmin')->name('admin.')->group(function () use ($registerAdminResourceRoutes) {
    Route::middleware('admin.guest')->controller(AuthController::class)->group(function () {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store')->name('authenticate');
    });

    Route::middleware('admin.auth')->group(function () use ($registerAdminResourceRoutes) {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::controller(SiteLayoutSettingController::class)->group(function () {
            Route::get('/site-layout', 'edit')->name('site-layout.edit');
            Route::put('/site-layout', 'update')->name('site-layout.update');
        });

        Route::get('/site-layout/custom-js-css', [SiteLayoutSettingController::class, 'editCustomCode'])->name('site-layout.custom-code.edit');
        Route::put('/site-layout/custom-js-css', [SiteLayoutSettingController::class, 'updateCustomCode'])->name('site-layout.custom-code.update');

        Route::controller(\App\Http\Controllers\Admin\RobotsSettingController::class)->group(function () {
            Route::get('/robots-settings', 'edit')->name('robots-settings.edit');
            Route::put('/robots-settings', 'update')->name('robots-settings.update');
            Route::post('/robots-settings/reset', 'reset')->name('robots-settings.reset');
        });

        Route::get('/robots-settings/sitemap', [PageMetaController::class, 'sitemap'])->name('robots-settings.sitemap');

        Route::controller(PageMetaController::class)->group(function () {
            Route::get('/pages', 'edit')->name('pages.edit');
            Route::put('/pages', 'update')->name('pages.update');
            Route::post('/pages/toggle-sitemap', 'toggleSitemap')->name('pages.toggle-sitemap');
            Route::post('/pages/toggle-sitemap-url', 'toggleSitemapUrl')->name('pages.toggle-sitemap-url');

            Route::get('/site-pages', 'index')->name('site-pages.index');
            Route::get('/site-pages/create', 'create')->name('site-pages.create');
            Route::post('/site-pages', 'store')->name('site-pages.store');
            Route::post('/site-pages/toggle-status', 'toggleStatus')->name('site-pages.toggle-status');
            Route::delete('/site-pages/delete', 'deletePage')->name('site-pages.delete');
        });

        Route::get('/blogs/categories', [BlogController::class, 'categories'])->name('blogs.categories');
        Route::post('/blogs/categories', [BlogController::class, 'storeCategory'])->name('blogs.categories.store');
        Route::put('/blogs/categories', [BlogController::class, 'updateCategory'])->name('blogs.categories.update');
        Route::delete('/blogs/categories', [BlogController::class, 'destroyCategory'])->name('blogs.categories.destroy');
        Route::post('/blogs/bulk-action', [BlogController::class, 'bulkAction'])->name('blogs.bulk-action');
        Route::get('/blogs/tags', [BlogController::class, 'tags'])->name('blogs.tags');
        Route::post('/blogs/tags', [BlogController::class, 'storeTag'])->name('blogs.tags.store');
        Route::put('/blogs/tags', [BlogController::class, 'updateTag'])->name('blogs.tags.update');
        Route::delete('/blogs/tags', [BlogController::class, 'destroyTag'])->name('blogs.tags.destroy');

        Route::get('/portfolios/categories', [AdminPortfolioController::class, 'categories'])->name('portfolios.categories');
        Route::post('/portfolios/categories', [AdminPortfolioController::class, 'storeCategory'])->name('portfolios.categories.store');
        Route::put('/portfolios/categories', [AdminPortfolioController::class, 'updateCategory'])->name('portfolios.categories.update');
        Route::delete('/portfolios/categories', [AdminPortfolioController::class, 'destroyCategory'])->name('portfolios.categories.destroy');
        Route::post('/portfolios/bulk-action', [AdminPortfolioController::class, 'bulkAction'])->name('portfolios.bulk-action');
        Route::get('/portfolios/tags', [AdminPortfolioController::class, 'tags'])->name('portfolios.tags');
        Route::post('/portfolios/tags', [AdminPortfolioController::class, 'storeTag'])->name('portfolios.tags.store');
        Route::put('/portfolios/tags', [AdminPortfolioController::class, 'updateTag'])->name('portfolios.tags.update');
        Route::delete('/portfolios/tags', [AdminPortfolioController::class, 'destroyTag'])->name('portfolios.tags.destroy');
        $registerAdminResourceRoutes('blogs', 'blogs', BlogController::class, 'blog');
        Route::post('/blogs/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');
        $registerAdminResourceRoutes('portfolios', 'portfolios', AdminPortfolioController::class, 'portfolio');
        Route::post('/portfolios/{portfolio}/toggle-status', [AdminPortfolioController::class, 'toggleStatus'])->name('portfolios.toggle-status');
        $registerAdminResourceRoutes('videos', 'videos', AdminVideoController::class, 'video');
        Route::post('/videos/{video}/toggle-status', [AdminVideoController::class, 'toggleStatus'])->name('videos.toggle-status');
        Route::post('/videos/bulk-action', [AdminVideoController::class, 'bulkAction'])->name('videos.bulk-action');
        $registerAdminResourceRoutes('client-logos', 'client-logos', ClientLogoController::class, 'clientLogo');
        Route::post('/client-logos/{clientLogo}/toggle-status', [ClientLogoController::class, 'toggleStatus'])->name('client-logos.toggle-status');
        $registerAdminResourceRoutes('reviews', 'reviews', ReviewController::class, 'review');
        Route::post('/reviews/{review}/toggle-status', [ReviewController::class, 'toggleStatus'])->name('reviews.toggle-status');

        Route::prefix('contact-messages')
            ->name('contact-messages.')
            ->controller(AdminContactMessageController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{contactMessage}', 'show')->name('show');
                Route::get('/{contactMessage}/download', 'downloadAttachment')->name('download');
            });

        Route::prefix('fresher-hirings')
            ->name('fresher-hirings.')
            ->controller(AdminFresherHiringController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{fresherHiring}', 'show')->name('show');
                Route::get('/{fresherHiring}/download', 'downloadAttachment')->name('download');
                Route::patch('/{fresherHiring}/status', 'updateStatus')->name('update-status');
            });

        Route::prefix('internship-applications')
            ->name('internship-applications.')
            ->controller(AdminInternshipApplicationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{internshipApplication}', 'show')->name('show');
                Route::patch('/{internshipApplication}/status', 'updateStatus')->name('update-status');
            });
    });
});
