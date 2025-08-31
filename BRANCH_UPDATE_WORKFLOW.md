# Branch-Based Update Workflow - Quick Reference

## 🚀 Quick Start

### 1. Create Feature Branch
```bash
git checkout -b feature/new-feature
git push origin feature/new-feature
```

### 2. Make Changes & Commit
```bash
# Make your changes
git add .
git commit -m "Add new feature"
git push origin feature/new-feature
```

### 3. Generate Update Package

**Windows:**
```cmd
scripts\create-update.bat feature/new-feature 1.1.0 "New feature description"
```

**Linux/Mac:**
```bash
./scripts/create-update.sh feature/new-feature 1.1.0 "New feature description"
```

**Direct PHP:**
```bash
php scripts/create-update-package.php feature/new-feature 1.1.0 "New feature description"
```

### 4. Upload & Apply Update
1. Go to **Admin Panel → System Updates**
2. Upload the generated ZIP file
3. Click "Upload & Apply Update"

## 📁 Generated Files

The script creates:
- `storage/app/temp/update-v1.1.0-feature-new-feature-2024-01-15-10-30-00.zip`

## 🔧 Script Options

### Basic Usage
```bash
# Auto-generate version and description
php scripts/create-update-package.php feature/new-feature
```

### Custom Version
```bash
# Specify custom version
php scripts/create-update-package.php feature/new-feature 1.2.0
```

### Full Customization
```bash
# Specify version and description
php scripts/create-update-package.php feature/new-feature 1.2.0 "Major UI improvements"
```

## 📋 What Gets Included

### ✅ Included
- All application files
- Configuration files
- Views and assets
- Routes and controllers
- Database migrations

### ❌ Excluded
- `.git/` directory
- `vendor/` dependencies
- `node_modules/` dependencies
- `storage/` application data
- `bootstrap/cache/` cache files
- Backup and temp files

## 🔒 Safety Features

- **Automatic Backup**: Creates backup before update
- **Version Validation**: Ensures higher version number
- **Structure Validation**: Checks update package format
- **Rollback Support**: Download previous backups
- **Update History**: Tracks all updates with branch info

## 📊 Update Tracking

The system tracks:
- Version number
- Branch name
- Commit hash and message
- Author information
- Update date and time
- Description and changes

## 🚨 Important Notes

1. **Always test** your branch before creating update
2. **Use semantic versioning** (MAJOR.MINOR.PATCH)
3. **Keep backups** of important installations
4. **Test updates** on staging first
5. **Document changes** in update description

## 🔧 Troubleshooting

### Common Issues

**"Branch doesn't exist"**
- Check branch name spelling
- Ensure branch is pushed to remote

**"Version must be higher"**
- Increment version number
- Check current version in config/app.php

**"Invalid update structure"**
- Ensure update.json exists
- Check files/ directory structure

**"Unable to extract"**
- Verify ZIP file integrity
- Check file permissions and disk space

### Manual Rollback
1. Download backup from admin panel
2. Extract and replace files
3. Update version in config/app.php
4. Clear caches and run migrations

## 📈 Best Practices

1. **Feature Branches**: Keep branches focused and small
2. **Testing**: Test thoroughly before creating update
3. **Documentation**: Document all changes clearly
4. **Versioning**: Use consistent version numbering
5. **Backups**: Keep multiple backup copies

## 🎯 Example Workflow

```bash
# 1. Create and work on feature
git checkout -b feature/user-dashboard
# ... make changes ...
git add . && git commit -m "Add user dashboard"
git push origin feature/user-dashboard

# 2. Create update package
php scripts/create-update-package.php feature/user-dashboard 1.1.0 "Add user dashboard with analytics"

# 3. Upload through admin panel
# Go to Admin → System Updates → Upload ZIP

# 4. Verify update
# Test new features and monitor for issues
```

This workflow provides a robust, secure, and efficient way to manage updates from different branches while maintaining full control over the update process.
