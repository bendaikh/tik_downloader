# TikTok Downloader - Update System Guide

## Overview

The TikTok Downloader script includes a comprehensive automatic update system that allows buyers to easily update their installations without manual file replacement. This system provides:

- **Automatic Updates**: Upload update packages and apply them automatically
- **Backup Management**: Automatic backups before each update
- **Update History**: Track all applied updates
- **Version Control**: Ensure updates are applied in the correct order
- **Safety Features**: Validation and rollback capabilities

## For Developers (Script Sellers)

### Generating Update Packages

Use the built-in Artisan command to generate update packages:

```bash
# Generate a full update package
php artisan update:generate 1.1.0 --description="Bug fixes and performance improvements"

# Generate update with specific changes
php artisan update:generate 1.2.0 \
    --description="New features and security updates" \
    --changes="Added new download format" \
    --changes="Fixed security vulnerability" \
    --changes="Improved error handling"

# Generate update with specific files only
php artisan update:generate 1.1.1 \
    --description="Hotfix for critical issue" \
    --files="app/Http/Controllers/DownloadController.php" \
    --files="resources/views/download.blade.php"

# Specify custom output directory
php artisan update:generate 1.3.0 \
    --description="Major feature update" \
    --output="/path/to/updates"
```

### Update Package Structure

The generated update package will have this structure:

```
update_v1.1.0_2024-01-15_14-30-25.zip
├── update.json
└── files/
    ├── app/
    ├── config/
    ├── resources/
    ├── routes/
    └── ... (all updated files)
```

### update.json Format

```json
{
    "version": "1.1.0",
    "description": "Bug fixes and performance improvements",
    "changes": [
        "Fixed download timeout issue",
        "Improved error handling",
        "Added new download format"
    ],
    "release_date": "2024-01-15T14:30:25.000000Z",
    "compatibility": {
        "min_version": "1.0.0",
        "max_version": "1.1.0"
    },
    "requirements": {
        "php": "8.0.0",
        "laravel": "9.0.0"
    }
}
```

### Best Practices for Updates

1. **Version Numbering**: Use semantic versioning (MAJOR.MINOR.PATCH)
2. **Descriptive Changes**: Provide clear descriptions of what changed
3. **Test Updates**: Always test update packages before distribution
4. **Backward Compatibility**: Ensure updates don't break existing functionality
5. **Documentation**: Include migration notes if database changes are involved

### Distribution

1. Generate the update package using the Artisan command
2. Upload the ZIP file to your distribution platform (CodeCanyon, etc.)
3. Provide clear instructions to buyers on how to apply the update
4. Include the update package in your documentation

## For Buyers (Script Users)

### Accessing the Update System

1. Log in to your admin panel
2. Navigate to **System Updates** in the sidebar
3. You'll see the current version and update options

### Applying Updates

1. **Download the Update Package**: Get the latest update ZIP file from the seller
2. **Upload the Package**: 
   - Drag and drop the ZIP file onto the upload area, or
   - Click to browse and select the file
3. **Apply the Update**: Click "Upload & Apply Update"
4. **Wait for Completion**: The system will:
   - Create a backup of your current installation
   - Extract and validate the update package
   - Apply the changes
   - Clear caches and run migrations
   - Update the version number

### Update Requirements

The update package must:
- Be a valid ZIP file (max 100MB)
- Contain `update.json` with version information
- Contain `files/` directory with updated files
- Have a version higher than your current version

### Backup Management

- **Automatic Backups**: A backup is created before each update
- **Download Backups**: You can download previous backups if needed
- **Backup Location**: Backups are stored in `storage/app/backups/`

### Update History

The system tracks all applied updates with:
- Version numbers
- Application dates
- Descriptions
- List of changes

### Troubleshooting

#### Update Fails
- Check that the ZIP file is valid and not corrupted
- Ensure the version is higher than your current version
- Verify the update package structure is correct
- Check server logs for detailed error messages

#### Rollback
If an update causes issues:
1. Download a backup from the backup management section
2. Extract the backup to your server
3. Restore your database if needed
4. Clear caches: `php artisan cache:clear`

#### Common Issues

**"Invalid update structure"**
- The ZIP file doesn't contain the required `update.json` and `files/` directory

**"Version must be higher than current version"**
- You're trying to apply an older version or the same version

**"Unable to extract update file"**
- The ZIP file is corrupted or password-protected

**"Update failed"**
- Check file permissions on your server
- Ensure sufficient disk space
- Verify PHP has write permissions to the application directory

### Security Considerations

- Only download update packages from trusted sources
- Verify the update package hasn't been tampered with
- Keep backups before applying any updates
- Test updates on a staging environment first if possible

## Technical Details

### File Locations

- **Update Controller**: `app/Http/Controllers/Admin/UpdateController.php`
- **Update View**: `resources/views/admin/update.blade.php`
- **Update Command**: `app/Console/Commands/GenerateUpdatePackage.php`
- **Backups**: `storage/app/backups/`
- **Update History**: `storage/app/updates/update_history.json`
- **Temp Files**: `storage/app/temp/updates/`

### Database Changes

The update system doesn't automatically handle database migrations. If your update includes database changes:

1. Include migration files in the update package
2. The system will run `php artisan migrate --force` after applying files
3. Document any manual database steps required

### Excluded Files

The update system automatically excludes:
- `vendor/` directory
- `node_modules/` directory
- `storage/` directory (except specific subdirectories)
- `.env` file
- Git files
- Log files
- Cache files

### Performance

- Update packages are processed in memory for efficiency
- Large files are handled in chunks
- Temporary files are automatically cleaned up
- Caches are cleared after updates for optimal performance

## Support

If you encounter issues with the update system:

1. Check the error messages in the admin panel
2. Review server logs for detailed information
3. Ensure your server meets the requirements
4. Contact the script seller for support

---

**Note**: Always backup your installation before applying any updates, even though the system creates automatic backups.
