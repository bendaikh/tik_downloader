<?php

namespace App\Service;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class StorableConfig
{
    protected array $configs = [];
    protected Repository $repository;
    protected Application $app;
    protected Filesystem $filesystem;
    protected array $original = [];

    // List of config namespaces that are allowed to be persisted to storage.
    // Added `services.openai` for OpenAI credentials and `ai` for general AI feature flags.
    const ALLOWED_CONFIGS = ['app', 'services.openai', 'ai', 'services.microsoft', 'payments', 'analytics', 'search_console', 'seo'];

    /**
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->filesystem = $app->make('files');
        $this->readConfigs();
    }

    /**
     * Merge the loaded configuration with the runtime configuration.
     */
    public function merge(Repository $repository): void
    {
        $this->repository = $repository;

        foreach ($this->configs as $key => $values) {
            $this->mergeConfig($key, $values);
        }
    }

    /**
     * Save the storable configuration.
     */
    public function save(): bool
    {
        return $this->write();
    }

    /**
     * Put a value in the storable configuration.
     */
    public function put(string $key, array $values): static
    {
        // Only allow configs that are in the allowed list
        if (!in_array($key, static::ALLOWED_CONFIGS)) {
            return $this;
        }

        $this->configs[$key] = array_merge(
            $this->configs[$key] ?? [],
            $values
        );

        $this->mergeConfig($key, $values);

        return $this;
    }

    /**
     * Load the storable configuration from disk.
     * @throws FileNotFoundException
     */
    protected function readConfigs(): void
    {
        $files = $this->filesystem->files(storage_path('app/config'));
        foreach ($files as $file) {
            $key = basename($file, '.json');
            if (!in_array($key, static::ALLOWED_CONFIGS))
                continue;

            $this->configs[$key] = json_decode(
                $this->filesystem->get($file),
                true
            );
        }
    }

    /**
     * Merge the given values with the runtime configuration.
     */
    protected function mergeConfig(string $config, array $values): void
    {
        foreach (array_keys($values) as $key) {
            $path = $config . '.' . $key;
            if (Arr::has($this->original, $path))
                continue;

            $value = $this->repository->get($path);
            Arr::set($this->original, $path, $value);
        }

        $this->repository->set(
            $config, array_merge(
            $this->repository->get($config, []),
            $values
        ));
    }

    /**
     * Write the storable configuration to disk.
     */
    protected function write(): bool
    {
        foreach ($this->configs as $config => $values) {
            // Only write configs that are in the allowed list
            if (!in_array($config, static::ALLOWED_CONFIGS)) {
                continue;
            }
            
            $file = storage_path("app/config/$config.json");
            $content = json_encode($values, JSON_PRETTY_PRINT);
            if(!file_exists($file)) touch($file);

            if (!$this->filesystem->put($file, $content))
                return false;
        }
        return true;
    }
}
