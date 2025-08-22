<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateDatabaseTables extends Command
{
    protected $signature = 'db:create-tables';
    protected $description = 'Create all database tables with correct structure';

    public function handle()
    {
        $this->info('Creating database tables...');

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
                host varchar(255) NOT NULL,
                port int NOT NULL,
                username varchar(255) NULL,
                password varchar(255) NULL,
                is_active tinyint(1) NOT NULL DEFAULT 1,
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

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        foreach ($tables as $tableName => $sql) {
            $this->info("Creating table: $tableName");
            try {
                // Drop table if exists
                DB::statement("DROP TABLE IF EXISTS $tableName");
                // Create table with correct structure
                DB::statement($sql);
                $this->info("✓ Created table: $tableName");
            } catch (\Exception $e) {
                $this->error("✗ Failed to create table $tableName: " . $e->getMessage());
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                return 1;
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Insert migration records
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
        ];

        $this->info("Inserting migration records...");
        foreach ($migrations as $migration) {
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => 1,
            ]);
        }

        $this->info('✓ All tables created successfully!');
        return 0;
    }
}
