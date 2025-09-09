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

			// Apply the update (now supports deletions)
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

		// Ensure extraction directory exists and is writable
		if (!File::exists($extractPath)) {
			File::makeDirectory($extractPath, 0755, true);
		}
		
		if (!is_writable($extractPath)) {
			$zip->close();
			throw new Exception('Extraction directory is not writable: ' . $extractPath);
		}

		// Log ZIP contents for debugging
		$zipEntries = [];
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$zipEntries[] = $zip->getNameIndex($i);
		}
		\Log::info('ZIP file contents', ['entries' => $zipEntries]);

		// Try extraction with different methods for better compatibility
		$extractionSuccess = false;
		
		// Method 1: Standard extractTo
		if ($zip->extractTo($extractPath)) {
			$extractionSuccess = true;
		} else {
			\Log::warning('Standard ZIP extraction failed, trying manual extraction');
			
			// Method 2: Manual extraction (fallback for problematic ZIP files)
			try {
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$filename = $zip->getNameIndex($i);
					if ($filename === false) continue;
					
					// Skip directory entries
					if (substr($filename, -1) === '/') {
						$dirPath = $extractPath . $filename;
						if (!File::exists($dirPath)) {
							File::makeDirectory($dirPath, 0755, true);
						}
						continue;
					}
					
					// Extract file
					$filePath = $extractPath . $filename;
					$fileDir = dirname($filePath);
					
					if (!File::exists($fileDir)) {
						File::makeDirectory($fileDir, 0755, true);
					}
					
					$fileContent = $zip->getFromIndex($i);
					if ($fileContent !== false) {
						File::put($filePath, $fileContent);
					}
				}
				$extractionSuccess = true;
				\Log::info('Manual ZIP extraction completed successfully');
			} catch (Exception $e) {
				\Log::error('Manual ZIP extraction failed', ['error' => $e->getMessage()]);
			}
		}
		
		if (!$extractionSuccess) {
			$zip->close();
			throw new Exception('Unable to extract update file to: ' . $extractPath);
		}

		$zip->close();
		
		// Log extracted contents for debugging
		$extractedContents = [];
		if (File::exists($extractPath)) {
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($extractPath, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST
			);
			foreach ($iterator as $file) {
				$extractedContents[] = str_replace($extractPath, '', $file->getPathname());
			}
		}
		\Log::info('Extracted contents', ['path' => $extractPath, 'contents' => $extractedContents]);
	}

	private function validateUpdateStructure($extractPath)
	{
		$requiredFiles = [
			'update.json',
			'files/',
		];

		// Log the actual directory structure for debugging
		$actualContents = [];
		if (File::exists($extractPath)) {
			$iterator = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($extractPath, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::SELF_FIRST
			);
			foreach ($iterator as $file) {
				$relativePath = str_replace($extractPath, '', $file->getPathname());
				$actualContents[] = ltrim(str_replace('\\', '/', $relativePath), '/');
			}
		}
		\Log::info('Validation - Actual extracted structure', ['contents' => $actualContents]);

		foreach ($requiredFiles as $file) {
			$fullPath = $extractPath . $file;
			$exists = File::exists($fullPath);
			
			\Log::info('Validation check', [
				'required' => $file,
				'full_path' => $fullPath,
				'exists' => $exists,
				'is_dir' => $exists ? is_dir($fullPath) : false,
				'is_file' => $exists ? is_file($fullPath) : false
			]);
			
			if (!$exists) {
				// Try case-insensitive search for files/ directory (Linux case sensitivity issue)
				if ($file === 'files/') {
					$foundAlternative = false;
					foreach ($actualContents as $content) {
						if (strtolower($content) === 'files' || strtolower($content) === 'files/') {
							\Log::warning('Found files directory with different case', ['found' => $content, 'expected' => $file]);
							$foundAlternative = true;
							break;
						}
					}
					if (!$foundAlternative) {
						throw new Exception("Invalid update structure. Missing: {$file}. Found contents: " . implode(', ', $actualContents));
					}
				} else {
					throw new Exception("Invalid update structure. Missing: {$file}. Found contents: " . implode(', ', $actualContents));
				}
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

		\Log::info('Starting update application', [
			'version' => $updateInfo['version'],
			'branch' => $updateInfo['branch'],
			'filesPath' => $filesPath,
			'basePath' => base_path()
		]);

		// Create backup if requested
		if ($createBackup) {
			$this->createBackup($updateInfo);
		}

		// Apply deletions first if a manifest exists
		$deletionsManifest = $extractPath . 'deletions.json';
		if (File::exists($deletionsManifest)) {
			$toDelete = json_decode(File::get($deletionsManifest), true) ?: [];
			$deleted = [];
			$notFound = [];
			foreach ($toDelete as $relPath) {
				$abs = base_path($relPath);
				try {
					if (File::isDirectory($abs)) {
						File::deleteDirectory($abs);
						$deleted[] = $relPath;
					} elseif (File::exists($abs)) {
						File::delete($abs);
						$deleted[] = $relPath;
					} else {
						$notFound[] = $relPath;
					}
				} catch (Exception $e) {
					\Log::warning('Failed to delete during update', ['path' => $relPath, 'error' => $e->getMessage()]);
				}
			}
			\Log::info('Applied deletions', ['deleted' => $deleted, 'not_found' => $notFound]);
		}

		// Copy changed files
		$this->copyFiles($filesPath, base_path());

		// Update version and branch in config
		$this->updateVersion($updateInfo['version'], $updateInfo['branch']);

		// Log update
		$this->logUpdate($updateInfo);

		\Log::info('Update application completed successfully', [
			'version' => $updateInfo['version'],
			'branch' => $updateInfo['branch']
		]);
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
				'VENDOR_PLACEHOLDER',
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
		// Ensure source path exists and is a directory
		if (!File::exists($sourcePath) || !is_dir($sourcePath)) {
			throw new Exception("Source path does not exist or is not a directory: {$sourcePath}");
		}

		// Ensure destination path exists
		if (!File::exists($destinationPath)) {
			File::makeDirectory($destinationPath, 0755, true);
		}

		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($sourcePath),
			\RecursiveIteratorIterator::LEAVES_ONLY
		);

		$copiedFiles = [];
		$failedFiles = [];

		foreach ($files as $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($sourcePath));
				// Normalize path separators for cross-platform compatibility
				$relativePath = str_replace('\\', '/', $relativePath);
				$destPath = rtrim($destinationPath, '/\\') . DIRECTORY_SEPARATOR . ltrim($relativePath, '/\\');

				// Create directory if it doesn't exist
				$destDir = dirname($destPath);
				if (!File::exists($destDir)) {
					File::makeDirectory($destDir, 0755, true);
				}

				// Copy file with robust error handling and proper encoding preservation
				try {
					\Log::info("Attempting to copy file", [
						'source' => $filePath,
						'dest' => $destPath,
						'relative' => $relativePath
					]);
					
					// Use File::copy() as primary method
					if (File::copy($filePath, $destPath)) {
						$copiedFiles[] = $relativePath;
						\Log::info("File copied successfully", ['file' => $relativePath]);
					} else {
						\Log::warning("File::copy failed, trying manual copy", ['file' => $relativePath]);
						// Fallback to manual copy if File::copy fails
						$content = file_get_contents($filePath);
						if ($content !== false && file_put_contents($destPath, $content) !== false) {
							$copiedFiles[] = $relativePath;
							\Log::info("Manual copy successful", ['file' => $relativePath]);
						} else {
							$failedFiles[] = $relativePath . ' (Copy failed)';
							\Log::error("Manual copy failed", ['file' => $relativePath]);
						}
					}
				} catch (Exception $e) {
					$failedFiles[] = $relativePath . ' (Error: ' . $e->getMessage() . ')';
					\Log::error("File copy exception", ['file' => $relativePath, 'error' => $e->getMessage()]);
				}
			}
		}

		// Log the results
		\Log::info('Update files copied successfully:', $copiedFiles);
		if (!empty($failedFiles)) {
			\Log::warning('Update files failed to copy:', $failedFiles);
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
		// Clear all caches to ensure changes are visible
		Artisan::call('config:clear');
		Artisan::call('cache:clear');
		Artisan::call('view:clear');
		Artisan::call('route:clear');
		Artisan::call('clear-compiled');

		// Refresh theme public assets in case theme files changed
		try {
			Artisan::call('theme:clear-cache');
			Artisan::call('theme:link');
		} catch (\Throwable $e) {
			\Log::warning('Theme refresh failed after update', ['error' => $e->getMessage()]);
		}

		// Try to reset PHP OPcache if enabled so code changes take effect immediately
		if (function_exists('opcache_reset')) {
			@opcache_reset();
		}

		// Run database migrations if any migration files were included in the update
		$this->runMigrations();

		// Optimize
		Artisan::call('optimize');

		// Log post-update tasks completion
		\Log::info('Post-update tasks completed successfully');
	}

	private function runMigrations()
	{
		try {
			\Log::info('Checking for pending migrations...');
			
			// Check if there are pending migrations
			$pendingMigrations = Artisan::call('migrate:status');
			$output = Artisan::output();
			
			// Look for pending migrations in the output
			if (strpos($output, 'Pending') !== false) {
				\Log::info('Pending migrations found, running migrations...');
				
				// Run migrations with force flag (for production)
				$migrationResult = Artisan::call('migrate', ['--force' => true]);
				
				if ($migrationResult === 0) {
					\Log::info('Database migrations completed successfully');
				} else {
					\Log::error('Database migrations failed', ['exit_code' => $migrationResult]);
				}
			} else {
				\Log::info('No pending migrations found');
			}
			
		} catch (\Throwable $e) {
			\Log::error('Migration execution failed', ['error' => $e->getMessage()]);
			// Don't throw exception - let the update continue even if migrations fail
		}
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

	/**
	 * Debug method to analyze ZIP file structure
	 * This can help diagnose extraction issues in production
	 */
	public function debugZipStructure(Request $request)
	{
		$request->validate([
			'update_file' => 'required|file|mimes:zip|max:102400',
		]);

		try {
			$file = $request->file('update_file');
			$tempPath = storage_path('app/temp/debug/');
			
			// Create temp directory if it doesn't exist
			if (!File::exists($tempPath)) {
				File::makeDirectory($tempPath, 0755, true);
			}

			// Store the uploaded file
			$fileName = 'debug_' . time() . '.zip';
			$filePath = $tempPath . $fileName;
			$file->move($tempPath, $fileName);

			$zip = new ZipArchive();
			$result = $zip->open($filePath);
			
			if ($result !== true) {
				File::delete($filePath);
				return response()->json([
					'error' => 'Unable to open ZIP file',
					'code' => $result
				], 400);
			}

			$zipEntries = [];
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$zipEntries[] = [
					'index' => $i,
					'name' => $zip->getNameIndex($i),
					'size' => $zip->statIndex($i)['size'] ?? 0,
					'compressed_size' => $zip->statIndex($i)['comp_size'] ?? 0,
					'is_dir' => substr($zip->getNameIndex($i), -1) === '/'
				];
			}

			$zip->close();
			File::delete($filePath);

			return response()->json([
				'total_files' => $zip->numFiles,
				'entries' => $zipEntries,
				'has_update_json' => in_array('update.json', array_column($zipEntries, 'name')),
				'has_files_dir' => in_array('files/', array_column($zipEntries, 'name')),
				'php_version' => PHP_VERSION,
				'zip_extension' => extension_loaded('zip') ? 'loaded' : 'not loaded',
				'os' => PHP_OS,
				'path_separator' => DIRECTORY_SEPARATOR
			]);

		} catch (Exception $e) {
			return response()->json([
				'error' => $e->getMessage()
			], 500);
		}
	}
}
