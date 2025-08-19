<?php

namespace App\Service\Theme;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Fluent;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read string $version
 * @property-read array $locales
 * @property-read array $rtl_support
 * @property-read string $preview
 */
class Theme extends Fluent
{
    public ThemeManager $manager;
    protected string $hashedId;

    public function __construct(array $attributes, ThemeManager $manager)
    {
        parent::__construct($attributes);
        $this->manager = $manager;
        $version = 'theme-' . $this->id . '-' . $this->version;
        $this->hashedId = md5($version);
    }

    public function locales(): array
    {
        return $this->locales ?? ['en' => 'English'];
    }

    public function rtlLocales(): array
    {
        return $this->rtl_support ?? [];
    }

    public function isRTL(string $locale): bool
    {
        return in_array($locale, $this->rtlLocales());
    }

    public function path(string $path = null): string
    {
        if (is_null($path)) {
            return $this->manager->themePath($this->id);
        }

        $path = ltrim($path, '/');
        return $this->manager->themePath($this->id . DIRECTORY_SEPARATOR . $path);
    }

    public function resourcesPath(string $path = null): string
    {
        if (is_null($path)) {
            return $this->path('resources');
        }

        $path = ltrim($path, '/');
        return $this->path("resources" . DIRECTORY_SEPARATOR . $path);
    }

    public function getProvider(): string
    {
        if (!$this->manager->filesystem->exists($this->path('ServiceProvider.php'))) {
            return ThemeServiceProvider::class;
        }

        return 'Themes\\' . $this->id . '\ServiceProvider';
    }

    public function isActivated(): bool
    {
        return $this->manager->currentTheme() === $this;
    }

    public function getScreenshot(): ?string
    {
        if (!$this->preview) {
            return null;
        }
        return $this->path($this->preview);
    }

    public function asset(string $path): string
    {
        $path = ltrim($path, '/');
        return asset('/theme-assets/' . $path) . '?v=' . $this->hashedId;
    }


}
