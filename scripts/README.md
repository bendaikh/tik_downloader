# Update Scripts

This directory contains scripts to help you create update packages from git branches.

## 📁 Available Scripts

### `create-update-package.php`
The main PHP script that creates update packages from git branches.

**Usage:**
```bash
php scripts/create-update-package.php <branch-name> [version] [description]
```

### `create-update.bat`
Windows batch script wrapper for easy usage on Windows systems.

**Usage:**
```cmd
scripts\create-update.bat feature/new-feature 1.1.0 "New feature description"
```

### `create-update.sh`
Linux/Mac shell script wrapper with colored output and error handling.

**Usage:**
```bash
./scripts/create-update.sh feature/new-feature 1.1.0 "New feature description"
```

## 🚀 Quick Examples

### Basic Usage
```bash
# Create update from feature branch (auto version)
php scripts/create-update-package.php feature/new-ui

# Create update with custom version
php scripts/create-update-package.php feature/new-ui 1.2.0

# Create update with version and description
php scripts/create-update-package.php feature/new-ui 1.2.0 "New UI improvements"
```

### Windows Users
```cmd
# Double-click the .bat file or run from command prompt
scripts\create-update.bat feature/new-ui 1.2.0 "New UI improvements"
```

### Linux/Mac Users
```bash
# Make script executable (first time only)
chmod +x scripts/create-update.sh

# Run the script
./scripts/create-update.sh feature/new-ui 1.2.0 "New UI improvements"
```

## 📋 What the Scripts Do

1. **Validate Environment**
   - Check if running from git repository
   - Verify target branch exists
   - Ensure PHP is available

2. **Prepare Branch**
   - Switch to target branch
   - Pull latest changes
   - Get commit information

3. **Generate Metadata**
   - Auto-generate version if not provided
   - Create update.json with all required fields
   - Include commit hash, author, and message

4. **Package Files**
   - Exclude unnecessary directories
   - Create proper directory structure
   - Generate ZIP file with timestamp

5. **Cleanup**
   - Remove temporary files
   - Switch back to original branch

## 📁 Generated Files

The scripts create update packages in:
```
storage/app/temp/update-v{version}-{branch}-{timestamp}.zip
```

## 🔧 Requirements

- Git repository with branches
- PHP 8.0+ with ZIP extension
- Command line access
- Proper file permissions

## 📖 Documentation

For detailed information, see:
- [UPDATE_SYSTEM_GUIDE.md](../UPDATE_SYSTEM_GUIDE.md) - Comprehensive guide
- [BRANCH_UPDATE_WORKFLOW.md](../BRANCH_UPDATE_WORKFLOW.md) - Quick reference

## 🚨 Troubleshooting

### Common Issues

**"Not a git repository"**
- Run script from project root directory
- Ensure .git directory exists

**"Branch doesn't exist"**
- Check branch name spelling
- Ensure branch is pushed to remote

**"PHP not found"**
- Install PHP or add to PATH
- Ensure ZIP extension is enabled

**"Permission denied"**
- Check file permissions
- Run with appropriate user privileges

### Getting Help

If you encounter issues:
1. Check error messages carefully
2. Verify all requirements are met
3. Test with a simple branch first
4. Check the comprehensive documentation
