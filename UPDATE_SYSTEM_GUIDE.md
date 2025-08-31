# Branch-Based Update System Guide

This guide explains how to use the enhanced update system for managing updates from different git branches.

## 🚀 Overview

The update system allows you to:
- Create update packages from any git branch
- Upload and apply updates through the admin panel
- Track update history with branch information
- Automatically create backups before updates
- Validate update packages before installation

## 📋 Prerequisites

- Git repository with branches
- PHP 8.0+ with ZIP extension
- Admin access to the application

## 🔧 Workflow

### Step 1: Create a New Branch

```bash
# Create and switch to a new branch
git checkout -b feature/new-feature

# Make your changes
# ... edit files ...

# Commit your changes
git add .
git commit -m "Add new feature"

# Push to remote (optional)
git push origin feature/new-feature
```

### Step 2: Generate Update Package

```bash
# Generate update package from your branch
scripts/create-update.bat feature/new-feature 1.1.0 "New feature description"
```

### Step 3: Apply Update

1. Go to **Admin Panel → System Updates**
2. Upload the generated ZIP file
3. Click "Upload & Apply Update"

### Step 4: Run Migrations (if needed)

If your update includes database changes, run migrations separately:

```bash
php artisan migrate --force
```
```

### Step 2: Generate Update Package

Use the provided script to create an update package:

```bash
# Basic usage
php scripts/create-update-package.php feature/new-feature

# With custom version and description
php scripts/create-update-package.php feature/new-feature 1.1.0 "New feature implementation"

# The script will:
# 1. Switch to the target branch
# 2. Pull latest changes
# 3. Create update.json with metadata
# 4. Package files into ZIP
# 5. Switch back to original branch
```

### Step 3: Upload and Apply Update

1. Go to **Admin Panel → System Updates**
2. Upload the generated ZIP file
3. The system will:
   - Validate the update package
   - Create a backup of current version
   - Apply the update
   - Update version and branch information
   - Clear caches and run migrations

## 📁 Update Package Structure

Your update package must have this structure:

```
update-package.zip
├── update.json          # Update metadata
└── files/              # Application files
    ├── app/
    ├── config/
    ├── resources/
    ├── routes/
    └── ... (other directories)
```

### update.json Format

```json
{
    "version": "1.1.0",
    "branch": "feature/new-feature",
    "description": "New feature implementation",
    "commit_hash": "abc123def456...",
    "commit_message": "Add new feature",
    "author": "Your Name",
    "date": "2024-01-15 10:30:00",
    "changes": [
        "Added new UI components",
        "Improved performance",
        "Fixed bugs"
    ]
}
```

## 🛠️ Script Usage

### Create Update Package Script

```bash
php scripts/create-update-package.php <branch-name> [version] [description]
```

**Parameters:**
- `branch-name`: The git branch to create update from (required)
- `version`: New version number (optional, auto-generated if not provided)
- `description`: Update description (optional)

**Examples:**

```bash
# Create update from feature branch
php scripts/create-update-package.php feature/new-ui

# Create update with custom version
php scripts/create-update-package.php feature/new-ui 1.2.0

# Create update with custom version and description
php scripts/create-update-package.php feature/new-ui 1.2.0 "New UI improvements and bug fixes"
```

### What the Script Does

1. **Validates Environment**
   - Checks if running from git repository
   - Verifies target branch exists

2. **Prepares Branch**
   - Switches to target branch
   - Pulls latest changes
   - Gets commit information

3. **Generates Metadata**
   - Auto-generates version if not provided
   - Creates update.json with all required fields
   - Includes commit hash, author, and message

4. **Packages Files**
   - Excludes unnecessary directories (.git, vendor, storage, etc.)
   - Creates proper directory structure
   - Generates ZIP file with timestamp

5. **Cleanup**
   - Removes temporary files
   - Switches back to original branch

## 🔒 Security Features

### Automatic Backup
- Creates backup before each update
- Backup includes version information
- Stored in `storage/app/backups/`

### Validation
- Validates ZIP file integrity
- Checks update.json structure
- Ensures version is higher than current
- Validates required fields

### Rollback
- Download previous backups
- Manual rollback by restoring backup
- Update history tracking

## 📊 Update History

The system tracks:
- Version number
- Branch name
- Update date and time
- Description and changes
- Commit information
- Author details

## 🚨 Important Notes

### Before Creating Updates

1. **Test Your Changes**
   - Ensure your branch works correctly
   - Run tests if available
   - Test on staging environment

2. **Version Management**
   - Use semantic versioning (MAJOR.MINOR.PATCH)
   - Always increment version number
   - Document breaking changes

3. **Backup Strategy**
   - Keep multiple backups
   - Test backup restoration
   - Store backups securely

### Excluded Directories

The update package excludes:
- `.git/` - Git repository data
- `vendor/` - Composer dependencies
- `node_modules/` - NPM dependencies
- `storage/` - Application storage
- `bootstrap/cache/` - Framework cache
- Temporary and backup files

### Post-Update Tasks

After update installation:
- Clear all caches
- Run database migrations
- Optimize application
- Test functionality

## 🔧 Troubleshooting

### Common Issues

1. **"Invalid update structure"**
   - Ensure update.json exists
   - Check files/ directory structure
   - Verify ZIP file integrity

2. **"Version must be higher"**
   - Increment version number
   - Check current version in config/app.php

3. **"Missing required field"**
   - Ensure update.json has all required fields
   - Check JSON syntax

4. **"Unable to extract update file"**
   - Verify ZIP file is not corrupted
   - Check file permissions
   - Ensure sufficient disk space

### Manual Rollback

If update fails:

1. Download backup from admin panel
2. Extract backup to temporary location
3. Replace application files with backup
4. Update version in config/app.php
5. Clear caches and run migrations

## 📈 Best Practices

### Development Workflow

1. **Feature Branches**
   - Create feature branches for new development
   - Keep branches focused and small
   - Test thoroughly before creating update

2. **Version Control**
   - Use meaningful commit messages
   - Tag releases appropriately
   - Maintain clean git history

3. **Testing**
   - Test updates on staging first
   - Verify all functionality works
   - Check for breaking changes

### Update Management

1. **Regular Updates**
   - Schedule regular updates
   - Keep track of update history
   - Document all changes

2. **Communication**
   - Notify users of updates
   - Document new features
   - Provide migration guides if needed

3. **Monitoring**
   - Monitor application after updates
   - Check error logs
   - Verify performance

## 🎯 Example Workflow

Here's a complete example workflow:

```bash
# 1. Create feature branch
git checkout -b feature/user-dashboard
git push origin feature/user-dashboard

# 2. Make changes
# ... edit files ...

# 3. Commit changes
git add .
git commit -m "Add user dashboard with analytics"
git push origin feature/user-dashboard

# 4. Create update package
php scripts/create-update-package.php feature/user-dashboard 1.1.0 "Add user dashboard with analytics"

# 5. Upload through admin panel
# Go to Admin → System Updates → Upload the generated ZIP file

# 6. Verify update
# Check that new features work correctly
# Monitor for any issues
```

This system provides a robust, secure, and user-friendly way to manage updates from different branches while maintaining full control over the update process.
