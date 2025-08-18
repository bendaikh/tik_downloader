<?php

namespace Themes\TTDown;

use App\Service\Theme\ThemeServiceProvider;
use Illuminate\Support\Facades\Blade;

class ServiceProvider extends ThemeServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        
        // Register Blade component namespace
        Blade::componentNamespace("Themes\\TTDown\\Views", 'ttdown');
        
        // Load theme routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }
}
