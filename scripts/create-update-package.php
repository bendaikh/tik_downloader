<?php

/**
 * Create Update Package Script
 * 
 * This script helps you create update packages from git branches
 * Usage: php scripts/create-update-package.php <branch-name> [version] [description]
 */

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from command line');
}

// Check if branch name is provided
if ($argc < 2) {
    echo "Usage: php scripts/create-update-package.php <branch-name> [version] [description]\n";
    echo "Example: php scripts/create-update-package.php feature/new-ui 1.1.0 'New UI improvements'\n";
    exit(1);
}

$branchName = $argv[1];
$version = $argv[2] ?? null;
$description = $argv[3] ?? 'Update from branch: ' . $branchName;

// Get current directory
$projectRoot = dirname(__DIR__);

// Check if we're in a git repository
if (!is_dir($projectRoot . '/.git')) {
    die("Error: Not a git repository. Please run this script from the project root.\n");
}

// Get current branch
$currentBranch = trim(shell_exec('git branch --show-current'));
if ($currentBranch === $branchName) {
    echo "Warning: You're already on branch '$branchName'. Make sure you have the latest changes.\n";
} else {
    echo "Current branch: $currentBranch\n";
    echo "Target branch: $branchName\n";
    
    // Check if branch exists
    $branchExists = shell_exec("git branch --list $branchName");
    if (empty($branchExists)) {
        die("Error: Branch '$branchName' does not exist.\n");
    }
    
    // Switch to target branch
    echo "Switching to branch '$branchName'...\n";
    shell_exec("git checkout $branchName");
    
    // Pull latest changes
    echo "Pulling latest changes...\n";
    shell_exec("git pull origin $branchName");
}

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
echo "Branch: $branchName\n";
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
    'branch' => $branchName,
    'description' => $description,
    'commit_hash' => $commitHash,
    'commit_message' => $commitMessage,
    'author' => $author,
    'date' => date('Y-m-d H:i:s'),
    'changes' => [
        'Updated from branch: ' . $branchName,
        'Commit: ' . substr($commitHash, 0, 8),
        'Message: ' . $commitMessage
    ]
];

file_put_contents($tempDir . '/update.json', json_encode($updateJson, JSON_PRETTY_PRINT));

// Create files directory
$filesDir = $tempDir . '/files';
mkdir($filesDir, 0755, true);

// Copy project files (excluding certain directories)
$excludeDirs = [
    '.git',
    'vendor',
    'node_modules',
    'storage',
    'bootstrap/cache',
    'storage/app/temp',
    'storage/app/backups',
    'storage/app/updates',
    'database/migrations',  // Exclude migrations to prevent conflicts
    'database/seeders'      // Exclude seeders as well
];

echo "Copying files...\n";
copyDirectory($projectRoot, $filesDir, $excludeDirs);

// Create ZIP file
$zipFileName = "update-v{$version}-{$branchName}-" . date('Y-m-d-H-i-s') . ".zip";
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
if ($currentBranch !== $branchName) {
    echo "Switching back to branch: $currentBranch\n";
    shell_exec("git checkout $currentBranch");
}

/**
 * Copy directory recursively
 */
function copyDirectory($source, $destination, $excludeDirs = []) {
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $dir = opendir($source);
    while (($file = readdir($dir)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $sourcePath = $source . '/' . $file;
        $destPath = $destination . '/' . $file;
        
        // Check if directory should be excluded
        $relativePath = str_replace(dirname($source) . '/', '', $sourcePath);
        $shouldExclude = false;
        foreach ($excludeDirs as $excludeDir) {
            if (strpos($relativePath, $excludeDir) === 0) {
                $shouldExclude = true;
                break;
            }
        }
        
        if ($shouldExclude) {
            continue;
        }
        
        if (is_dir($sourcePath)) {
            copyDirectory($sourcePath, $destPath, $excludeDirs);
        } else {
            copy($sourcePath, $destPath);
        }
    }
    closedir($dir);
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
