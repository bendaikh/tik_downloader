<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Traits\Wizard;
use App\Traits\WriteEnv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Throwable;

class ConfigureDatabaseController extends Controller
{
    use WriteEnv;
    use Wizard;

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'database_host' => ['required', 'string'],
            'database_port' => ['required', 'numeric'],
            'database_name' => ['required', 'string'],
            'database_username' => ['required', 'string'],
            'database_password' => ['nullable', 'string'],
        ]);


        try {

            if (!$this->testConnection($request)) {
                return back()->exceptInput('database_password')
                    ->with('message', [
                        'type' => 'error',
                        'content' => "Database connection failed. Please check your credentials and try again."
                    ]);
            }

            $env = [
                'DB_HOST' => $request->get('database_host'),
                'DB_PORT' => $request->get('database_port'),
                'DB_DATABASE' => $request->get('database_name'),
                'DB_USERNAME' => $request->get('database_username'),
                'DB_PASSWORD' => $request->get('database_password', '') ?? '',
            ];

            if (!$this->writeENV($env)) {
                return back()->exceptInput('database_password')
                    ->with('message', [
                        'type' => 'error',
                        'content' => "Failed to write database credentials to .env file."
                    ]);
            }

            // Clear config cache and reload configuration
            Artisan::call('config:clear', ['--quiet' => true]);
            Artisan::call('cache:clear', ['--quiet' => true]);
            
            // Test connection again after writing .env
            if (!$this->testConnection($request)) {
                return back()->exceptInput('database_password')
                    ->with('message', [
                        'type' => 'error',
                        'content' => "Database connection failed after writing configuration. Please check your credentials."
                    ]);
            }

                    // Set longer timeout for migration
        set_time_limit(600); // 10 minutes
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1G');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('max_input_time', 600);
            
                    try {
            // Clear any cached config
            Artisan::call('config:clear', ['--quiet' => true]);
            
            // Create all tables directly using SQL - this is the complete solution
            $tables = [
                'migrations' => 'CREATE TABLE migrations (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    migration varchar(255) NOT NULL,
                    batch int NOT NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'users' => 'CREATE TABLE users (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name varchar(255) NOT NULL,
                    email varchar(255) NOT NULL,
                    email_verified_at timestamp NULL,
                    role varchar(255) NOT NULL DEFAULT "admin",
                    password varchar(255) NOT NULL,
                    remember_token varchar(100) NULL,
                    created_at timestamp NULL,
                    updated_at timestamp NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'password_resets' => 'CREATE TABLE password_resets (
                    email varchar(255) NOT NULL PRIMARY KEY,
                    token varchar(255) NOT NULL,
                    created_at timestamp NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'failed_jobs' => 'CREATE TABLE failed_jobs (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    uuid varchar(255) NOT NULL UNIQUE,
                    connection text NOT NULL,
                    queue text NOT NULL,
                    payload longtext NOT NULL,
                    exception longtext NOT NULL,
                    failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'videos' => 'CREATE TABLE videos (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    tiktok_id varchar(255) NOT NULL UNIQUE,
                    title varchar(255) NULL,
                    description text NULL,
                    thumbnail varchar(255) NULL,
                    video_url varchar(255) NULL,
                    duration int NULL,
                    views bigint NULL,
                    likes bigint NULL,
                    shares bigint NULL,
                    comments bigint NULL,
                    created_at timestamp NULL,
                    updated_at timestamp NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'proxies' => 'CREATE TABLE proxies (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    enabled tinyint(1) NOT NULL DEFAULT 1,
                    protocol varchar(255) NOT NULL,
                    hostname varchar(255) NOT NULL,
                    port int NOT NULL,
                    auth tinyint(1) NOT NULL DEFAULT 0,
                    username varchar(255) NULL,
                    password varchar(255) NULL,
                    created_at timestamp NULL,
                    updated_at timestamp NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'products' => 'CREATE TABLE products (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name varchar(255) NOT NULL,
                    description text NULL,
                    image varchar(255) NULL,
                    affiliate_url varchar(255) NOT NULL,
                    price decimal(10,2) NULL,
                    currency varchar(3) NOT NULL DEFAULT "USD",
                    is_active tinyint(1) NOT NULL DEFAULT 1,
                    sort_order int NOT NULL DEFAULT 0,
                    created_at timestamp NULL,
                    updated_at timestamp NULL
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'blogs' => 'CREATE TABLE blogs (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    title varchar(255) NOT NULL,
                    slug varchar(255) NOT NULL UNIQUE,
                    description text NULL,
                    content longtext NOT NULL,
                    featured_image varchar(255) NULL,
                    meta_title varchar(255) NULL,
                    meta_description text NULL,
                    is_published tinyint(1) NOT NULL DEFAULT 0,
                    is_ai_generated tinyint(1) NOT NULL DEFAULT 0,
                    ai_prompts json NULL,
                    author_id bigint unsigned NOT NULL,
                    published_at timestamp NULL,
                    created_at timestamp NULL,
                    updated_at timestamp NULL,
                    INDEX idx_is_published_published_at (is_published, published_at),
                    INDEX idx_slug (slug),
                    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'visits' => 'CREATE TABLE visits (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    visitor_id varchar(255) NOT NULL,
                    session_id varchar(255) NULL,
                    ip_address varchar(45) NULL,
                    country_code varchar(2) NULL,
                    country_name varchar(255) NULL,
                    user_agent varchar(255) NULL,
                    device_type ENUM("desktop", "mobile", "tablet", "other") NOT NULL DEFAULT "other",
                    browser varchar(255) NULL,
                    created_at timestamp NULL,
                    updated_at timestamp NULL,
                    INDEX idx_visitor_id (visitor_id),
                    INDEX idx_session_id (session_id),
                    INDEX idx_country_code (country_code),
                    INDEX idx_device_type (device_type),
                    INDEX idx_browser (browser)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                
                'downloads' => 'CREATE TABLE downloads (
                    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    visitor_id varchar(255) NULL,
                    session_id varchar(255) NULL,
                    ip_address varchar(45) NULL,
                    user_agent varchar(255) NULL,
                    video_id varchar(255) NULL,
                    type varchar(255) NULL,
                    bytes bigint unsigned NULL,
                    created_at timestamp NULL,
                    updated_at timestamp NULL,
                    INDEX idx_visitor_id (visitor_id),
                    INDEX idx_session_id (session_id),
                    INDEX idx_video_id (video_id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
            ];
            
            // Disable foreign key checks to avoid constraint issues
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            
            // Create all tables
            foreach ($tables as $tableName => $sql) {
                try {
                    // Drop table if exists
                    DB::statement("DROP TABLE IF EXISTS $tableName");
                    // Create table with correct structure
                    DB::statement($sql);
                } catch (Exception $e) {
                    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                    return back()
                        ->exceptInput('database_password')
                        ->with('message', [
                            'type' => 'error',
                            'content' => "Failed to create table $tableName: " . $e->getMessage()
                        ]);
                }
            }
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            // Create migrations table if it doesn't exist
            if (!Schema::hasTable('migrations')) {
                Schema::create('migrations', function (Blueprint $table) {
                    $table->id();
                    $table->string('migration');
                    $table->integer('batch');
                });
            }
            
            // Mark all migrations as run
            $migrations = [
                '2014_10_12_000000_create_users_table',
                '2014_10_12_100000_create_password_resets_table',
                '2019_08_19_000000_create_failed_jobs_table',
                '2022_09_01_104112_create_videos_table',
                '2022_09_04_105549_create_proxies_table',
                '2025_08_15_183855_create_products_table',
                '2025_08_16_114615_create_blogs_table',
                '2025_08_18_000001_create_visits_table',
                '2025_08_18_000002_create_downloads_table',
                '2025_08_30_122712_add_enabled_column_to_proxies_table',
            ];
            
            // Clear existing migration records
            DB::table('migrations')->truncate();
            
            // Insert migration records
            foreach ($migrations as $index => $migration) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => 1,
                ]);
            }
                

                
            } catch (Throwable $migrationException) {
                logger()->error('Migration error: ' . $migrationException->getMessage());
                return back()
                    ->exceptInput('database_password')
                    ->with('message', [
                        'type' => 'error',
                        'content' => "Migration failed: " . $migrationException->getMessage()
                    ]);
            }

            $this->storeStep("installer.admin");

            return redirect()
                ->route('installer.admin')
                ->with('message', [
                    'type' => 'success',
                    'content' => "Database configured successfully."
                ]);

        } catch (Throwable $exception) {
            logger()->error($exception);

            return back()
                ->exceptInput('database_password')
                ->with('message', [
                    'type' => 'error',
                    'content' => "An error occurred while configuring database. Please try again."
                ]);
        }
    }

    function testConnection(Request $request): bool
    {
        $connection = config('database.default');

        $config = [
            'driver' => config('database.connections.' . $connection . '.driver'),
            'host' => $request->input('database_host'),
            'port' => $request->input('database_port'),
            'database' => $request->input('database_name'),
            'username' => $request->input('database_username'),
            'password' => $request->input('database_password', "") ?? "",
        ];

        $baseConfig = config('database.connections.' . $connection);
        config(["database.connections.$connection" => array_merge($baseConfig, $config)]);

        try {
            DB::purge();
            DB::reconnect($connection);
            DB::connection($connection)->getPDO();
            return true;
        } catch (Throwable $e) {
            logger()->error($e);
            return false;
        }
    }
}
