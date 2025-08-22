# TikTok Video Downloader - Installation Guide

## 🚀 Quick Installation

This TikTok Video Downloader script comes with an **automatic installation wizard** that makes setup incredibly easy. The installation process takes less than 2 minutes and guides you through every step.

## 📋 Prerequisites

Before installing, make sure your server meets these requirements:

### Server Requirements
- **PHP Version**: 8.0.2 or higher (8.1.0+ recommended)
- **Web Server**: Apache or Nginx
- **Database**: MySQL 5.7+ or MariaDB 10.2+

### PHP Extensions
- cURL (7.55.0+)
- OpenSSL (1.0.2k+)
- PDO
- fileinfo
- mbstring
- xml
- json
- zip
- gd
- intl

### PHP Settings
- `allow_url_fopen` = On
- `memory_limit` = 128M or higher
- `max_execution_time` = 300 or higher
- `upload_max_filesize` = 64M or higher
- `post_max_size` = 64M or higher

## 🔧 Installation Steps

### Step 1: Upload Files
1. Download the script from CodeCanyon
2. Extract the ZIP file
3. Upload all files to your web server's public directory (usually `public_html` or `www`)
4. Make sure the files are uploaded to the root of your domain

### Step 2: Set Permissions
Set the following directory permissions:
```bash
chmod 755 artisan
chmod 644 public/index.php
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 themes/config.json
```

### Step 3: Run the Installation Wizard
1. Open your browser and navigate to: `https://yourdomain.com/install`
2. The installation wizard will automatically start
3. Follow the step-by-step guide:

#### Step 3.1: Introduction
- Read and accept the terms of service
- Click "Agree and Start Installation"

#### Step 3.2: Requirements Check
- The system will automatically check all server requirements
- Make sure all requirements are met (green checkmarks)
- Click "Continue" when all requirements pass

#### Step 3.3: Database Configuration
- Enter your database details:
  - **Database Host**: Usually `localhost` or `127.0.0.1`
  - **Database Port**: Usually `3306`
  - **Database Name**: Your database name
  - **Database Username**: Your database username
  - **Database Password**: Your database password
- Click "Migrate" to set up the database

#### Step 3.4: Create Admin Account
- Enter your admin account details:
  - **Name**: Your full name
  - **Email**: Your email address
  - **Password**: Choose a strong password
- Click "Create Admin" to create your account

#### Step 3.5: Finish Installation
- Click "Clean Up and Finish Installation"
- The system will complete the setup and redirect you to your site

## 🎉 Installation Complete!

After installation, you can:
- **Frontend**: Visit your domain to use the TikTok downloader
- **Admin Panel**: Visit `https://yourdomain.com/admin` to access the admin panel
- **Login**: Use the admin credentials you created during installation

## 🔧 Manual Installation (Advanced)

If you prefer manual installation or need to troubleshoot:

### 1. Configure Environment
1. Copy `.env.example` to `.env`
2. Edit `.env` file with your configuration:
```env
APP_NAME="TikTok Video Downloader"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Run Database Migrations
```bash
php artisan migrate
```

### 4. Create Storage Links
```bash
php artisan storage:link
php artisan theme:link
```

### 5. Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. Create Admin User
```bash
php artisan make:user
```

## 🛠️ Troubleshooting

### Common Issues

#### 1. "Database connection failed"
- Check your database credentials
- Ensure the database exists
- Verify database user permissions

#### 2. "Permission denied" errors
- Set correct file permissions
- Ensure web server can write to storage directory
- Check SELinux settings (if applicable)

#### 3. "cURL not found" error
- Install cURL extension: `sudo apt-get install php-curl`
- Restart web server after installation

#### 4. "OpenSSL not found" error
- Install OpenSSL extension: `sudo apt-get install php-openssl`
- Restart web server after installation

#### 5. Installation wizard not accessible
- Check if `.htaccess` file is present
- Ensure mod_rewrite is enabled (Apache)
- Check web server configuration

### Server-Specific Instructions

#### cPanel
1. Upload files via File Manager
2. Set permissions via File Manager
3. Create database via MySQL Databases
4. Run installation wizard

#### Plesk
1. Upload files via File Manager
2. Set permissions via File Manager
3. Create database via Databases
4. Run installation wizard

#### VPS/Dedicated Server
1. Upload files via FTP/SFTP
2. Set permissions via SSH
3. Create database via command line or phpMyAdmin
4. Run installation wizard

## 🔒 Security Recommendations

After installation:
1. Change default admin password
2. Enable HTTPS (SSL certificate)
3. Set up regular backups
4. Keep PHP and server software updated
5. Monitor error logs

## 📞 Support

If you encounter any issues during installation:
1. Check the troubleshooting section above
2. Review server error logs
3. Ensure all requirements are met
4. Contact support with detailed error information

## 📝 License

This script is licensed under the terms of your CodeCanyon purchase. Please refer to the license agreement included with your purchase.

---

**Note**: This script uses independent and unofficial APIs. It is not affiliated with, authorized, maintained, sponsored, or endorsed by TikTok or any of its affiliates or subsidiaries.
