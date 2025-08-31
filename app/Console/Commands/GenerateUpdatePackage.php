<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class GenerateUpdatePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:generate 
                            {version : The version number for this update}
                            {--description= : Description of the update}
                            {--changes=* : List of changes in this update}
                            {--files=* : Specific files to include (relative to project root)}
                            {--output= : Output directory for the update package}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an update package for distribution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version = $this->argument('version');
        $description = $this->option('description') ?: "Update to version {$version}";
        $changes = $this->option('changes') ?: [];
        $specificFiles = $this->option('files');
        $outputDir = $this->option('output') ?: storage_path('app/updates');

        $this->info("Generating update package for version {$version}...");

        // Create output directory
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Create temporary directory for update files
        $tempDir = storage_path('app/temp/update_package_' . time());
        File::makeDirectory($tempDir, 0755, true);

        try {
            // Create update.json
            $this->createUpdateJson($tempDir, $version, $description, $changes);

            // Create files directory
            $filesDir = $tempDir . '/files';
            File::makeDirectory($filesDir, 0755, true);

            // Copy files
            if (!empty($specificFiles)) {
                $this->copySpecificFiles($filesDir, $specificFiles);
            } else {
                $this->copyAllFiles($filesDir);
            }

            // Create ZIP package
            $zipPath = $this->createZipPackage($tempDir, $outputDir, $version);

            $this->info("Update package created successfully!");
            $this->info("Package location: {$zipPath}");
            $this->info("Package size: " . $this->formatFileSize(File::size($zipPath)));

            // Clean up
            File::deleteDirectory($tempDir);

        } catch (\Exception $e) {
            $this->error("Failed to generate update package: " . $e->getMessage());
            
            // Clean up on error
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            
            return 1;
        }

        return 0;
    }

    private function createUpdateJson($tempDir, $version, $description, $changes)
    {
        $updateData = [
            'version' => $version,
            'description' => $description,
            'changes' => $changes,
            'release_date' => now()->toISOString(),
            'compatibility' => [
                'min_version' => '1.0.0',
                'max_version' => $version,
            ],
            'requirements' => [
                'php' => '8.0.0',
                'laravel' => '9.0.0',
            ],
        ];

        File::put($tempDir . '/update.json', json_encode($updateData, JSON_PRETTY_PRINT));
        $this->line("✓ Created update.json");
    }

    private function copySpecificFiles($filesDir, $specificFiles)
    {
        $this->info("Copying specific files...");
        
        foreach ($specificFiles as $file) {
            $sourcePath = base_path($file);
            $destPath = $filesDir . '/' . $file;

            if (!File::exists($sourcePath)) {
                $this->warn("File not found: {$file}");
                continue;
            }

            // Create directory structure if needed
            $destDir = dirname($destPath);
            if (!File::exists($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }

            if (File::isFile($sourcePath)) {
                File::copy($sourcePath, $destPath);
                $this->line("✓ Copied: {$file}");
            } elseif (File::isDirectory($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
                $this->line("✓ Copied directory: {$file}");
            }
        }
    }

    private function copyAllFiles($filesDir)
    {
        $this->info("Copying all project files...");
        
        $excludeDirs = [
            'vendor',
            'node_modules',
            'storage',
            '.git',
            '.github',
            'tests',
            'storage/app/backups',
            'storage/app/updates',
            'storage/app/temp',
            'storage/logs',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
        ];

        $excludeFiles = [
            '.env',
            '.env.example',
            '.gitignore',
            'composer.lock',
            'package-lock.json',
            'yarn.lock',
            'README.md',
            'INSTALLATION.md',
            'DEMO_USER_GUIDE.md',
        ];

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(base_path()),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $copiedCount = 0;
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(base_path()) + 1);

                // Check if file should be excluded
                $shouldExclude = false;
                
                // Check excluded directories
                foreach ($excludeDirs as $excludeDir) {
                    if (strpos($relativePath, $excludeDir . '/') === 0) {
                        $shouldExclude = true;
                        break;
                    }
                }

                // Check excluded files
                if (in_array($relativePath, $excludeFiles)) {
                    $shouldExclude = true;
                }

                if (!$shouldExclude) {
                    $destPath = $filesDir . '/' . $relativePath;
                    $destDir = dirname($destPath);

                    if (!File::exists($destDir)) {
                        File::makeDirectory($destDir, 0755, true);
                    }

                    File::copy($filePath, $destPath);
                    $copiedCount++;
                }
            }
        }

        $this->line("✓ Copied {$copiedCount} files");
    }

    private function copyDirectory($source, $destination)
    {
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) + 1);
                $destPath = $destination . '/' . $relativePath;
                $destDir = dirname($destPath);

                if (!File::exists($destDir)) {
                    File::makeDirectory($destDir, 0755, true);
                }

                File::copy($filePath, $destPath);
            }
        }
    }

    private function createZipPackage($tempDir, $outputDir, $version)
    {
        $zipPath = $outputDir . '/update_v' . $version . '_' . date('Y-m-d_H-i-s') . '.zip';
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Unable to create ZIP file');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($tempDir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
        $this->line("✓ Created ZIP package");

        return $zipPath;
    }

    private function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}
