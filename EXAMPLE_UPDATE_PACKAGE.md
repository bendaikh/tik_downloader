# Example Update Package Structure

This document shows the structure of a valid update package that can be uploaded through the admin panel.

## Package Structure

```
update_v1.1.0_2024-01-15_14-30-25.zip
├── update.json
└── files/
    ├── app/
    │   ├── Http/
    │   │   └── Controllers/
    │   │       └── DownloadController.php
    │   └── Service/
    │       └── TikTok/
    │           └── TikTokService.php
    ├── config/
    │   └── app.php
    ├── resources/
    │   └── views/
    │       └── download.blade.php
    ├── routes/
    │   └── web.php
    └── database/
        └── migrations/
            └── 2024_01_15_000000_add_new_feature.php
```

## update.json Content

```json
{
    "version": "1.1.0",
    "description": "Bug fixes and performance improvements for TikTok downloader",
    "changes": [
        "Fixed download timeout issue for large videos",
        "Improved error handling and user feedback",
        "Added support for new TikTok video formats",
        "Enhanced security measures",
        "Optimized download performance"
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

## How to Create This Package

### Using the Artisan Command (Recommended)

```bash
# Generate a full update package
php artisan update:generate 1.1.0 \
    --description="Bug fixes and performance improvements for TikTok downloader" \
    --changes="Fixed download timeout issue for large videos" \
    --changes="Improved error handling and user feedback" \
    --changes="Added support for new TikTok video formats" \
    --changes="Enhanced security measures" \
    --changes="Optimized download performance"
```

### Manual Creation

1. Create a directory structure:
   ```bash
   mkdir -p update_package/files
   ```

2. Create the `update.json` file with the content above

3. Copy your updated files to the `files/` directory, maintaining the same structure as your project

4. Create a ZIP file:
   ```bash
   cd update_package
   zip -r ../update_v1.1.0_$(date +%Y-%m-%d_%H-%M-%S).zip .
   ```

## Validation Rules

The update system validates:

1. **File Format**: Must be a valid ZIP file
2. **File Size**: Maximum 100MB
3. **Structure**: Must contain `update.json` and `files/` directory
4. **Version**: Must be higher than current version
5. **JSON Format**: `update.json` must be valid JSON with required fields

## Testing Your Update Package

Before distributing:

1. Create a test installation
2. Upload and apply the update package
3. Verify all changes are applied correctly
4. Test functionality thoroughly
5. Check that the version number is updated

## Distribution

Once your update package is ready:

1. Upload it to your distribution platform (CodeCanyon, etc.)
2. Provide clear instructions to buyers
3. Include the update package in your documentation
4. Test the update process on a fresh installation

## Troubleshooting

If your update package fails validation:

- Check that the ZIP file is not corrupted
- Verify the `update.json` format is correct
- Ensure the version number is higher than the target version
- Confirm all required files are included in the `files/` directory
- Test the package on a local installation first
