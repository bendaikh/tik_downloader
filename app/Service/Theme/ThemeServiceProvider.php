<?php

namespace App\Service\Theme;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public Theme|null $theme = null;
    public ThemeManager|null $themeManager = null;

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        /** @var Theme $theme */
        $theme = $this->app->make('theme');
        $this->theme = $theme;
        $this->themeManager = $theme->manager;

        // Ensure theme assets are publicly available
        // If the public/theme-assets symbolic link (or directory) is missing (e.g. on fresh production deploy)
        // we try to recreate it automatically so that images and css are served correctly without requiring
        // a separate deployment step.
        $publicAssetsPath = public_path('theme-assets');
        $sourceAssetsPath  = $theme->resourcesPath('assets');

        // (Re)link or copy assets if the currently linked directory does not match the active theme.
        $needsRefresh = false;
        if (!file_exists($publicAssetsPath)) {
            $needsRefresh = true;
        } elseif (is_link($publicAssetsPath)) {
            // On most OSs readlink returns the target of the symlink (or false on failure)
            $needsRefresh = realpath(readlink($publicAssetsPath)) !== realpath($sourceAssetsPath);
        } else {
            // Compare a representative file (css/app.css) checksum to detect theme switch
            $publicCss  = $publicAssetsPath . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'app.css';
            $sourceCss  = $sourceAssetsPath  . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'app.css';
            if (file_exists($publicCss) && file_exists($sourceCss)) {
                $needsRefresh = md5_file($publicCss) !== md5_file($sourceCss);
            } else {
                $needsRefresh = true;
            }
        }

        if ($needsRefresh) {
            // Remove outdated link/directory first
            if (is_link($publicAssetsPath)) {
                // For symlinked directory simply unlink to avoid deleting real files
                unlink($publicAssetsPath);
            } else {
                \Illuminate\Support\Facades\File::deleteDirectory($publicAssetsPath);
            }

            // Copy (not symlink) to ensure assets exist even on systems where symlinks are restricted.
            \Illuminate\Support\Facades\File::copyDirectory($sourceAssetsPath, $publicAssetsPath);
        }

        // Load theme views
        $this->loadViewsFrom(
            $theme->resourcesPath('views'),
            'theme'
        );

        // Load theme locales
        $this->loadJsonTranslationsFrom($theme->path('lang'));

        // Provide theme config to views
        View::composer(['theme::*'], function ($view) {
            $view->with('theme', $this->theme);
        });

        Route::macro('localization', function () {
            return Route::prefix('{locale?}')->where(['locale', '[a-z]{2}']);
        });
    }
}
