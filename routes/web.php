<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::withoutMiddleware(['locale'])->group(function () {
    
    Route::get("download", \App\Http\Controllers\DownloadFileController::class)
        ->name("download");

    // Audio extraction routes
    Route::post('/extract-audio', [\App\Http\Controllers\AudioExtractionController::class, 'extractAudio'])
        ->name('extract-audio');
    Route::get('/extract-audio-download', [\App\Http\Controllers\AudioExtractionController::class, 'downloadExtractedAudio'])
        ->name('extract-audio-download');

    // Donation routes
    Route::get('/donate', [\App\Http\Controllers\DonationController::class, 'show'])->name('donation.show');
    Route::post('/donate/create-order', [\App\Http\Controllers\DonationController::class, 'createOrder'])->name('donation.create-order');
    Route::get('/donate/success', [\App\Http\Controllers\DonationController::class, 'success'])->name('donation.success');
    Route::get('/donate/cancel', [\App\Http\Controllers\DonationController::class, 'cancel'])->name('donation.cancel');
    Route::post('/donate/webhook', [\App\Http\Controllers\DonationController::class, 'webhook'])->name('donation.webhook');

    Route::redirect('/admin', '/admin/settings');

    Route::prefix('/admin')
        ->middleware('guest')
        ->group(function () {
            Route::get('/login', [\App\Http\Controllers\AuthenticatedSessionController::class, 'index'])
                ->name('login');
            Route::post('/login', [\App\Http\Controllers\AuthenticatedSessionController::class, 'store']);
        });

    Route::post('admin/logout', [\App\Http\Controllers\AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::prefix("/admin")
        ->middleware('auth')
        ->as('admin.')
        ->group(function () {
            Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
                ->name('analytics');
            Route::get("/settings", [\App\Http\Controllers\Admin\SettingsController::class, 'index'])
                ->name('settings');
            Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])
                ->name('settings.update');

            Route::get("/payment-settings", [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'index'])
                ->name('payment-settings');
            Route::post('/payment-settings', [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'update'])
                ->name('payment-settings.update');

            Route::get("/google-analytics", [\App\Http\Controllers\Admin\GoogleAnalyticsController::class, 'index'])
                ->name('google-analytics');
            Route::post('/google-analytics', [\App\Http\Controllers\Admin\GoogleAnalyticsController::class, 'update'])
                ->name('google-analytics.update');

            Route::get("/google-search-console", [\App\Http\Controllers\Admin\GoogleSearchConsoleController::class, 'index'])
                ->name('google-search-console');
            Route::post('/google-search-console', [\App\Http\Controllers\Admin\GoogleSearchConsoleController::class, 'update'])
                ->name('google-search-console.update');
            Route::post('/google-search-console/generate-sitemap', [\App\Http\Controllers\Admin\GoogleSearchConsoleController::class, 'generateSitemap'])
                ->name('google-search-console.generate-sitemap');

            Route::get("/safari", [\App\Http\Controllers\Admin\SafariController::class, 'index'])
                ->name('safari');
            Route::post('/safari', [\App\Http\Controllers\Admin\SafariController::class, 'update'])
                ->name('safari.update');

            Route::get("/ai-integration", [\App\Http\Controllers\Admin\AIIntegrationController::class, 'index'])
                ->name('ai-integration');
            Route::post('/ai-integration', [\App\Http\Controllers\Admin\AIIntegrationController::class, 'update'])
                ->name('ai-integration.update');

            Route::get("/edge-integration", [\App\Http\Controllers\Admin\EdgeIntegrationController::class, 'index'])
                ->name('edge-integration');
            Route::post('/edge-integration', [\App\Http\Controllers\Admin\EdgeIntegrationController::class, 'update'])
                ->name('edge-integration.update');

            Route::get("/proxies", [\App\Http\Controllers\Admin\ProxyController::class, 'index'])
                ->name('proxy');
            Route::get("/proxies/create", [\App\Http\Controllers\Admin\ProxyController::class, 'createForm'])
                ->name('proxy.create');
            Route::post("/proxies/create", [\App\Http\Controllers\Admin\ProxyController::class, 'create']);
            Route::get("/proxies/{proxy}/edit", [\App\Http\Controllers\Admin\ProxyController::class, 'editForm'])
                ->name('proxy.edit');
            Route::post("/proxies/{proxy}/edit", [\App\Http\Controllers\Admin\ProxyController::class, 'update']);
            Route::delete("/proxies/{proxy}/delete", [\App\Http\Controllers\Admin\ProxyController::class, 'destroy'])->name('proxy.delete');
            Route::post("/proxies/{proxy}/toggle", [\App\Http\Controllers\Admin\ProxyController::class, 'toggleProxyStatus'])
                ->name('proxy.toggle');

            Route::get('/appearance', [\App\Http\Controllers\Admin\AppearanceController::class, 'index'])
                ->name('appearance');

            Route::get('/appearance/{id}/screenshot', [\App\Http\Controllers\Admin\AppearanceController::class, 'screenshot'])
                ->name('appearance.theme.screenshot');
            Route::post('/appearance/{id}/activate', [\App\Http\Controllers\Admin\AppearanceController::class, 'activate'])
                ->name('appearance.theme.activate');
            Route::post('/appearance/{id}/clear-cache', [\App\Http\Controllers\Admin\AppearanceController::class, 'clearCache'])
                ->name('appearance.theme.clear-cache');

            Route::get('/me', [\App\Http\Controllers\Admin\MeController::class, 'index'])
                ->name('me');
            Route::post('/me', [\App\Http\Controllers\Admin\MeController::class, 'store']);

            Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

            // Blog routes
            Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
            Route::post('/blogs/generate-ai-content', [\App\Http\Controllers\Admin\BlogController::class, 'generateAiContent'])
                ->name('blogs.generate-ai-content');
            Route::post('/blogs/regenerate-content', [\App\Http\Controllers\Admin\BlogController::class, 'regenerateContent'])
                ->name('blogs.regenerate-content');

            // SEO Settings routes
            Route::get('/seo-settings', [\App\Http\Controllers\Admin\SeoSettingsController::class, 'index'])
                ->name('seo-settings');
            Route::post('/seo-settings', [\App\Http\Controllers\Admin\SeoSettingsController::class, 'update'])
                ->name('seo-settings.update');
            Route::post('/seo-settings/generate-sitemap', [\App\Http\Controllers\Admin\SeoSettingsController::class, 'generateSitemap'])
                ->name('seo-settings.generate-sitemap');

            // Live analytics endpoint
            Route::get('/analytics/live', function () {
                $now = now();
                $activeSince = $now->copy()->subMinutes(5);
                $liveUsers = \App\Models\Visit::where('created_at', '>=', $activeSince)
                    ->distinct('visitor_id')->count('visitor_id');
                $downloadsLast5m = \App\Models\Download::where('created_at', '>=', $activeSince)->count();
                $downloadsToday = \App\Models\Download::whereDate('created_at', $now->toDateString())->count();
                return response()->json([
                    'live_users' => $liveUsers,
                    'downloads_5m' => $downloadsLast5m,
                    'downloads_today' => $downloadsToday,
                ]);
            })->name('analytics.live');

            // Debug route for analytics
            Route::get('/analytics/debug', function () {
                $recentVisits = \App\Models\Visit::orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(['ip_address', 'country_code', 'country_name', 'created_at', 'user_agent']);
                
                $totalVisits = \App\Models\Visit::count();
                $visitsWithCountry = \App\Models\Visit::whereNotNull('country_code')
                    ->where('country_code', '!=', '')
                    ->count();
                
                return response()->json([
                    'total_visits' => $totalVisits,
                    'visits_with_country' => $visitsWithCountry,
                    'recent_visits' => $recentVisits,
                    'server_ip' => request()->ip(),
                    'headers' => [
                        'CF-IPCountry' => request()->headers->get('CF-IPCountry'),
                        'X-Forwarded-For' => request()->headers->get('X-Forwarded-For'),
                        'User-Agent' => request()->headers->get('User-Agent'),
                    ]
                ]);
            })->name('analytics.debug');
        });
});

// Robots.txt
Route::get('/robots.txt', [\App\Http\Controllers\RobotsController::class, 'index']);

// Sitemap
Route::get('/sitemap.xml', function () {
    if (config('seo.sitemap_enabled', false)) {
        $seoService = app(\App\Service\SeoService::class);
        $sitemap = $seoService->generateSitemap();
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
    
    abort(404);
});
