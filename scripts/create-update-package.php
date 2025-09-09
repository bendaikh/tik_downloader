<?php

/**
 * Create Update Package Script
 * 
 * This script helps you create update packages from git branches
 * Usage: php scripts/create-update-package.php <target-branch> [version] [description] [base-branch]
 */

if (php_sapi_name() !== 'cli') {
	die('This script can only be run from command line');
}

// Check if branch name is provided
if ($argc < 2) {
	echo "Usage: php scripts/create-update-package.php <target-branch> [version] [description] [base-branch]\n";
	echo "Example: php scripts/create-update-package.php feature/new-ui 1.1.0 'New UI improvements' update-app\n";
	exit(1);
}

$targetBranch = $argv[1];
$version = $argv[2] ?? null;
$description = $argv[3] ?? 'Update from branch: ' . $targetBranch;
$baseBranch = $argv[4] ?? null; // Optional base branch to diff against

// Get current directory
$projectRoot = dirname(__DIR__);

// Check if we're in a git repository
if (!is_dir($projectRoot . '/.git')) {
	die("Error: Not a git repository. Please run this script from the project root.\n");
}

// Get current branch
$currentBranch = trim(shell_exec('git branch --show-current'));
if ($currentBranch === $targetBranch) {
	echo "Warning: You're already on branch '$targetBranch'. Make sure you have the latest changes.\n";
} else {
	echo "Current branch: $currentBranch\n";
	echo "Target branch: $targetBranch\n";
	
	// Check if branch exists
	$branchExists = shell_exec("git branch --list $targetBranch");
	if (empty($branchExists)) {
		die("Error: Branch '$targetBranch' does not exist.\n");
	}
	
	// Switch to target branch
	echo "Switching to branch '$targetBranch'...\n";
	shell_exec("git checkout $targetBranch");
	
	// Pull latest changes
	echo "Pulling latest changes...\n";
	shell_exec("git pull origin $targetBranch");
}

// Determine base branch for diff (optional)
if (!$baseBranch) {
	// Try to read base branch from config/app.php 'branch' entry
	$configFile = $projectRoot . '/config/app.php';
	if (file_exists($configFile)) {
		$config = file_get_contents($configFile);
		if (preg_match("/'branch'\s*=>\s*['\"]([^'\"]*)['\"]/", $config, $matches)) {
			$baseBranch = $matches[1];
		}
	}
}

if (!$baseBranch) {
	// Fallback to previously active branch
	$baseBranch = $currentBranch ?: 'main';
}

echo "Base branch (for diff): $baseBranch\n";

// Get commit information
$commitHash = trim(shell_exec('git rev-parse HEAD'));
$commitMessage = trim(shell_exec('git log -1 --pretty=format:%s'));
$author = trim(shell_exec('git log -1 --pretty=format:%an'));

// If version not provided, generate one based on current version
if (!$version) {
	$configFile = $projectRoot . '/config/app.php';
	if (file_exists($configFile)) {
		$config = file_get_contents($configFile);
		if (preg_match("/'version'\s*=>\s*['\"]([^'\"]*)['\"]/", $config, $matches)) {
			$currentVersion = $matches[1];
			$versionParts = explode('.', $currentVersion);
			$versionParts[2] = (int)$versionParts[2] + 1; // Increment patch version
			$version = implode('.', $versionParts);
		}
	}
	
	if (!$version) {
		$version = '1.0.1'; // Default fallback
	}
}

echo "Creating update package for version: $version\n";
echo "Target Branch: $targetBranch\n";
echo "Base Branch: $baseBranch\n";
echo "Description: $description\n";
echo "Commit: $commitHash\n";

// Create temporary directory for update package
$tempDir = $projectRoot . '/storage/app/temp/update-package-' . time();
if (!is_dir($tempDir)) {
	mkdir($tempDir, 0755, true);
}

// Create update.json
$updateJson = [
	'version' => $version,
	'branch' => $targetBranch,
	'description' => $description,
	'commit_hash' => $commitHash,
	'commit_message' => $commitMessage,
	'author' => $author,
	'date' => date('Y-m-d H:i:s'),
	'changes' => [
		'Updated from branch: ' . $targetBranch,
		'Base: ' . $baseBranch,
		'Commit: ' . substr($commitHash, 0, 8),
		'Message: ' . $commitMessage
	]
];

file_put_contents($tempDir . '/update.json', json_encode($updateJson, JSON_PRETTY_PRINT));

// Create files directory
$filesDir = $tempDir . '/files';
mkdir($filesDir, 0755, true);

// Exclusions
$excludeDirs = [
	'.git',
	'VENDOR_PLACEHOLDER', // placeholder to align indexes
	'node_modules',
	'storage',
	'bootstrap/cache',
	'storage/app/temp',
	'storage/app/backups',
	'storage/app/updates',
	'database/migrations',
	'database/seeders'
];

// Compute diff between base and target
$diffCmd = sprintf('git diff --name-status %s...%s', escapeshellarg($baseBranch), escapeshellarg($targetBranch));
$diffOutput = shell_exec($diffCmd) ?: '';
$lines = array_filter(array_map('trim', explode("\n", $diffOutput)));

$changedFiles = [];
$deletedFiles = [];
foreach ($lines as $line) {
	// Formats: M\tpath, A\tpath, D\tpath, R100\told\tnew, etc.
	$parts = preg_split('/\s+/', $line);
	if (!$parts || count($parts) < 2) continue;
	$status = $parts[0];
	if ($status[0] === 'R' && count($parts) >= 3) {
		// Rename: treat as delete + add new
		$deletedFiles[] = $parts[1];
		$changedFiles[] = $parts[2];
		continue;
	}
	switch ($status) {
		case 'A':
		case 'M':
			$changedFiles[] = $parts[1];
			break;
		case 'D':
			$deletedFiles[] = $parts[1];
			break;
		default:
			// Handle other statuses loosely (e.g., C copies)
			if (isset($parts[1])) $changedFiles[] = $parts[1];
	}
}

$changedFiles = array_values(array_unique($changedFiles));
$deletedFiles = array_values(array_unique($deletedFiles));

if (empty($changedFiles) && empty($deletedFiles)) {
	echo "No differences detected between $baseBranch and $targetBranch.\n";
	// For safety, still package nothing but metadata so uploader will reject due to version policy if unchanged
}

echo "Changed files: " . count($changedFiles) . "\n";
echo "Deleted files: " . count($deletedFiles) . "\n";

// Copy only changed files into files/
foreach ($changedFiles as $relPath) {
	// Skip excluded directories
	foreach ($excludeDirs as $ex) {
		if ($ex === 'VENDOR_PLACEHOLDER') continue;
		if (strpos($relPath, $ex) === 0) {
			continue 2;
		}
	}
	$absSource = $projectRoot . '/' . $relPath;
	$absDest = $filesDir . '/' . $relPath;
	if (!file_exists($absSource)) continue; // In case it was moved/renamed to outside of repo
	$destDir = dirname($absDest);
	if (!is_dir($destDir)) mkdir($destDir, 0755, true);
	copy($absSource, $absDest);
}

// Write deletions manifest
if (!empty($deletedFiles)) {
	file_put_contents($tempDir . '/deletions.json', json_encode($deletedFiles, JSON_PRETTY_PRINT));
}

// Create ZIP file
$zipFileName = "update-v{$version}-{$targetBranch}-" . date('Y-m-d-H-i-s') . ".zip";
$zipPath = $projectRoot . '/storage/app/temp/' . $zipFileName;

echo "Creating ZIP file: $zipFileName\n";
createZip($tempDir, $zipPath);

// Clean up temporary directory
echo "Cleaning up...\n";
deleteDirectory($tempDir);

echo "\n✅ Update package created successfully!\n";
echo "File: $zipPath\n";
echo "Size: " . formatBytes(filesize($zipPath)) . "\n";
echo "\nYou can now upload this file through the admin panel.\n";

// Switch back to original branch if needed
if ($currentBranch !== $targetBranch) {
	echo "Switching back to branch: $currentBranch\n";
	shell_exec("git checkout $currentBranch");
}

/**
 * Create ZIP file
 */
function createZip($sourceDir, $zipPath) {
	$zip = new ZipArchive();
	if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
		die("Error: Cannot create ZIP file\n");
	}
	
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($sourceDir),
		RecursiveIteratorIterator::LEAVES_ONLY
	);
	
	foreach ($files as $file) {
		if (!$file->isDir()) {
			$filePath = $file->getRealPath();
			$relativePath = substr($filePath, strlen($sourceDir) + 1);
			$zip->addFile($filePath, $relativePath);
		}
	}
	
	$zip->close();
}

/**
 * Delete directory recursively
 */
function deleteDirectory($dir) {
	if (!is_dir($dir)) {
		return;
	}
	
	$files = array_diff(scandir($dir), ['.', '..']);
	foreach ($files as $file) {
		$path = $dir . '/' . $file;
		if (is_dir($path)) {
			deleteDirectory($path);
		} else {
			unlink($path);
		}
	}
	rmdir($dir);
}

/**
 * Format bytes to human readable format
 */
function formatBytes($bytes, $precision = 2) {
	$units = ['B', 'KB', 'MB', 'GB', 'TB'];
	
	for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
		$bytes /= 1024;
	}
	
	return round($bytes, $precision) . ' ' . $units[$i];
}
