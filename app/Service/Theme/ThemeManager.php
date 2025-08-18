<?php

namespace App\Service\Theme;

use App\Exceptions\ThemeConfigException;
use App\Exceptions\ThemeNotFoundException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ThemeManager
{
    protected Application $app;
    public Filesystem $filesystem;
    protected array $config;
    /**
     * @var Collection<Theme>
     */
    public Collection $themes;
    protected Theme|null $currentTheme;

    /**
     * @throws ContainerExceptionInterface
     * @throws FileNotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->filesystem = $this->app['files'];

        $this->loadConfig();
        $this->loadThemes();
        $this->loadCurrentTheme();
    }

    public function themePath(string $path): string
    {
        return base_path('themes/' . $path);
    }

    public function currentTheme(): Theme|null
    {
        if (isset($this->currentTheme)) {
            return $this->currentTheme;
        }

        return $this->loadCurrentTheme();
    }

    public function getTheme(string $id): Theme|null
    {
        return $this->themes->where('id', $id)->first();
    }

    /**
     * @return Collection<Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    protected function loadCurrentTheme(): Theme|null
    {
        $this->currentTheme = $this->themes->where(
            'id',
            $this->config['current'],
        )->first(
            null,
            function () {
                $placeholder = new \stdClass();

                $theme = $this
                    ->themes
                    ->where('id', $this->config['fallback'])
                    ->first(null, $placeholder);

                if ($theme === $placeholder && !app()->runningInConsole()) {
                    throw new ThemeNotFoundException('No fallback theme found.');
                }

                return null;
            });

        return $this->currentTheme;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws FileNotFoundException
     * @throws NotFoundExceptionInterface
     */
    protected function loadConfig(): void
    {
        $this->config = json_decode(
            $this->filesystem->get(base_path('themes/config.json')),
            true
        );
    }

    protected function loadThemes(): void
    {
        $dir = base_path('themes');
        $dirs = $this->filesystem->directories($dir);
        $this->themes = collect($dirs)->map(function ($dir) {
            $configPath = $dir . '/theme.json';
            if (!$this->filesystem->exists($configPath)) {
                return null;
            }
            $config = json_decode(
                $this->filesystem->get($configPath),
                true
            );
            if (!$config) {
                $themeName = basename($dir);
                throw new ThemeConfigException(
                    "Cannot load config file for [$themeName] theme at $configPath."
                );
            }

            return new Theme($config, $this);
        })->filter();
    }

    public function activateTheme(string $themeId): void
    {
        if (!$this->themes->where('id', $themeId)->count()) {
            throw new \InvalidArgumentException('Theme not found');
        }

        $this->config['current'] = $themeId;
        $this->filesystem->put(
            base_path('themes/config.json'),
            json_encode($this->config, JSON_PRETTY_PRINT)
        );

        $this->loadCurrentTheme();

        // Immediately synchronise public assets so that the next page load uses the new theme.
        $this->syncPublicAssets();
    }

    /**
     * Ensure that public/theme-assets is linked or copied from the currently active theme.
     */
    protected function syncPublicAssets(): void
    {
        $publicAssetsPath = public_path('theme-assets');
        $sourceAssetsPath = $this->currentTheme->resourcesPath('assets');

        // Delete existing directory or link
        if (file_exists($publicAssetsPath)) {
            // Try unlink first (handles symlink/junction). Suppress warnings if not a link.
            @unlink($publicAssetsPath);
            if (file_exists($publicAssetsPath)) {
                File::deleteDirectory($publicAssetsPath);
            }
        }

        // Recreate
        // On Windows, symlink creation often requires admin privileges and can silently fail.
        // To be safe and predictable across environments, we always copy the assets directory.
        File::copyDirectory($sourceAssetsPath, $publicAssetsPath);
    }
}
