<?php

namespace Themes\TikTokDark;

use App\Service\Theme\ThemeServiceProvider;

class ServiceProvider extends ThemeServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'TikTokDark');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }
}
