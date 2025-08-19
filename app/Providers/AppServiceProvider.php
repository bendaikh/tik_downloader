<?php

namespace App\Providers;

use App\Service\StorableConfig;
use App\Service\MicrosoftServicesService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton("config.storable", function($app) {
            return new StorableConfig($app);
        });

        $this->app->singleton(MicrosoftServicesService::class, function($app) {
            return new MicrosoftServicesService();
        });

        $this->app->singleton(\App\Service\Safari\SafariService::class, function($app) {
            return new \App\Service\Safari\SafariService();
        });

        $this->callAfterResolving('config', function($config, $app) {
            $app->make("config.storable")->merge($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
        public function boot(): void
    {
        // Schema::defaultStringLength(191);

        // Fake CodeSpikeX license API in local environment for development
        if ($this->app->environment('local')) {
            \Illuminate\Support\Facades\Http::fake([
                'api.codespikex.com/api/v2/license/*' => \Illuminate\Support\Facades\Http::response([
                    'status'     => 'active',
                    'usage'      => 0,
                    'max_usage'  => 1000,
                    'resets_at'  => now()->addMonth()->toDateString(),
                ], 200),
            ]);
        }

        // Ensure generic /assets symbolic link (or directory) exists for admin panel and other static assets
        $publicAssets = public_path('assets');
        if (!file_exists($publicAssets)) {
            $source = resource_path('assets');
            try {
                // Preferred: create symlink just like artisan storage:link would do
                \Illuminate\Support\Facades\File::link($source, $publicAssets);
            } catch (\Throwable $e) {
                // Fallback: copy directory when symlinks are unavailable (e.g. shared hosting)
                if (is_dir($source)) {
                    \Illuminate\Support\Facades\File::copyDirectory($source, $publicAssets);
                }
            }
        }
    }
}
