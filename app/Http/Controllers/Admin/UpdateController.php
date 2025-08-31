<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;
use Exception;

class UpdateController extends Controller
{
    public function index()
    {
        $currentVersion = config('app.version', '1.0.0');
        $currentBranch = config('app.branch', 'main');
        $updateHistory = $this->getUpdateHistory();
        
        return view('admin.update', compact('currentVersion', 'currentBranch', 'updateHistory'));
    }

    public function uploadUpdate(Request $request)
    {
        // Increase execution time for large updates
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');
        
        $request->validate([
            'update_file' => 'required|file|mimes:zip|max:102400', // 100MB max
        ]);

        try {
            $file = $request->file('update_file');
            $tempPath = storage_path('app/temp/updates/');
            
            // Create temp directory if it doesn't exist
            if (!File::exists($tempPath)) {
                File::makeDirectory($tempPath, 0755, true);
            }

            // Store the uploaded file
            $fileName = 'update_' . time() . '.zip';
            $filePath = $tempPath . $fileName;
            $file->move($tempPath, $fileName);

            // Extract and validate the update
            $extractPath = $tempPath . 'extracted_' . time() . '/';
            $this->extractUpdate($filePath, $extractPath);

            // Validate update structure
            $updateInfo = $this->validateUpdateStructure($extractPath);

                    // Apply the update
        $this->applyUpdate($extractPath, $updateInfo, $request->has('create_backup'));

            // Clean up
            File::delete($filePath);
            File::deleteDirectory($extractPath);

            // Run post-update tasks
            $this->runPostUpdateTasks();

            return redirect()->route('admin.update')
                ->with('success', "Update applied successfully! Updated to version {$updateInfo['version']} from branch {$updateInfo['branch']}.");

        } catch (Exception $e) {
            // Clean up on error
            if (isset($filePath) && File::exists($filePath)) {
                File::delete($filePath);
            }
            if (isset($extractPath) && File::exists($extractPath)) {
                File::deleteDirectory($extractPath);
            }

            return redirect()->route('admin.update')
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    private function extractUpdate($zipPath, $extractPath)
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath) !== true) {
            throw new Exception('Unable to open update file');
        }

        if (!$zip->extractTo($extractPath)) {
            $zip->close();
            throw new Exception('Unable to extract update file');
        }

        $zip->close();
    }

    private function validateUpdateStructure($extractPath)
    {
        $requiredFiles = [
            'update.json',
            'files/',
        ];

        foreach ($requiredFiles as $file) {
            if (!File::exists($extractPath . $file)) {
                throw new Exception("Invalid update structure. Missing: {$file}");
            }
        }

        // Validate update.json
        $updateInfo = json_decode(File::get($extractPath . 'update.json'), true);
        if (!$updateInfo || !isset($updateInfo['version'])) {
            throw new Exception('Invalid update.json file');
        }

        // Validate required fields
        $requiredFields = ['version', 'branch', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($updateInfo[$field])) {
                throw new Exception("Missing required field in update.json: {$field}");
            }
        }

        $currentVersion = config('app.version', '1.0.0');
        if (version_compare($updateInfo['version'], $currentVersion, '<=')) {
            throw new Exception('Update version must be higher than current version');
        }

        return $updateInfo;
    }

    private function applyUpdate($extractPath, $updateInfo, $createBackup = true)
    {
        $filesPath = $extractPath . 'files/';

        // Create backup if requested
        if ($createBackup) {
            $this->createBackup($updateInfo);
        }

        // Copy files
        $this->copyFiles($filesPath, base_path());

        // Update version and branch in config
        $this->updateVersion($updateInfo['version'], $updateInfo['branch']);

        // Log update
        $this->logUpdate($updateInfo);
    }

    private function createBackup($updateInfo)
    {
        try {
            $backupPath = storage_path('app/backups/backup_' . date('Y-m-d_H-i-s') . '_v' . config('app.version', '1.0.0') . '.zip');
            $backupDir = dirname($backupPath);
            
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $zip = new ZipArchive();
            if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new Exception('Unable to create backup ZIP file');
            }

            // Only backup essential files and directories
            $essentialDirs = [
                'app',
                'config', 
                'database',
                'resources',
                'routes',
                'themes',
                'public',
                'bootstrap',
                'artisan',
                'composer.json',
                'composer.lock',
                'package.json',
                '.env.example'
            ];

            $excludeDirs = [
                'vendor',
                'node_modules',
                'storage',
                '.git',
                'storage/app/temp',
                'storage/app/backups',
                'storage/app/updates',
                'storage/logs',
                'storage/framework/cache',
                'storage/framework/sessions',
                'storage/framework/views'
            ];

            foreach ($essentialDirs as $dir) {
                $fullPath = base_path($dir);
                if (File::exists($fullPath)) {
                    if (is_dir($fullPath)) {
                        $this->addDirectoryToZip($zip, $fullPath, $dir, $excludeDirs);
                    } else {
                        $zip->addFile($fullPath, $dir);
                    }
                }
            }

            $zip->close();
            
        } catch (Exception $e) {
            // Log backup failure but don't stop the update
            \Log::warning('Backup creation failed: ' . $e->getMessage());
            // Continue without backup
        }
    }

    private function addDirectoryToZip($zip, $dirPath, $relativePath, $excludeDirs)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $fileRelativePath = $relativePath . '/' . substr($filePath, strlen($dirPath) + 1);
                
                // Check if file should be excluded
                $shouldExclude = false;
                foreach ($excludeDirs as $excludeDir) {
                    if (strpos($fileRelativePath, $excludeDir) === 0) {
                        $shouldExclude = true;
                        break;
                    }
                }
                
                if (!$shouldExclude) {
                    $zip->addFile($filePath, $fileRelativePath);
                }
            }
        }
    }

    private function copyFiles($sourcePath, $destinationPath)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($sourcePath));
                $destPath = $destinationPath . $relativePath;

                // Create directory if it doesn't exist
                $destDir = dirname($destPath);
                if (!File::exists($destDir)) {
                    File::makeDirectory($destDir, 0755, true);
                }

                File::copy($filePath, $destPath);
            }
        }
    }

    private function updateVersion($newVersion, $newBranch)
    {
        $configPath = config_path('app.php');
        $config = File::get($configPath);
        
        // Update version in config
        $config = preg_replace(
            "/'version'\s*=>\s*['\"][^'\"]*['\"]/",
            "'version' => '{$newVersion}'",
            $config
        );

        // Update branch in config
        $config = preg_replace(
            "/'branch'\s*=>\s*['\"][^'\"]*['\"]/",
            "'branch' => '{$newBranch}'",
            $config
        );

        File::put($configPath, $config);
    }

    private function logUpdate($updateInfo)
    {
        $logPath = storage_path('app/updates/update_history.json');
        $logDir = dirname($logPath);
        
        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }

        $history = [];
        if (File::exists($logPath)) {
            $history = json_decode(File::get($logPath), true) ?: [];
        }

        $history[] = [
            'version' => $updateInfo['version'],
            'branch' => $updateInfo['branch'],
            'date' => now()->toISOString(),
            'description' => $updateInfo['description'] ?? '',
            'changes' => $updateInfo['changes'] ?? [],
            'commit_hash' => $updateInfo['commit_hash'] ?? null,
            'author' => $updateInfo['author'] ?? null,
        ];

        File::put($logPath, json_encode($history, JSON_PRETTY_PRINT));
    }

    private function runPostUpdateTasks()
    {
        // Clear caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // Note: Migrations are excluded from update packages to prevent conflicts
        // Run migrations separately using: php artisan migrate --force

        // Optimize
        Artisan::call('optimize');
    }

    private function getUpdateHistory()
    {
        $logPath = storage_path('app/updates/update_history.json');
        
        if (File::exists($logPath)) {
            return json_decode(File::get($logPath), true) ?: [];
        }

        return [];
    }

    public function downloadBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($backupPath)) {
            abort(404);
        }

        return response()->download($backupPath);
    }

    public function getBackups()
    {
        $backupDir = storage_path('app/backups/');
        $backups = [];

        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                    $backups[] = [
                        'name' => basename($file),
                        'size' => File::size($file),
                        'date' => date('Y-m-d H:i:s', File::lastModified($file)),
                    ];
                }
            }
        }

        return response()->json($backups);
    }
}
