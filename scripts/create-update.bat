@echo off
setlocal enabledelayedexpansion

echo ========================================
echo    TikTok Downloader - Update Creator
echo ========================================
echo.

if "%~1"=="" (
    echo Usage: create-update.bat ^<branch-name^> [version] [description]
    echo.
    echo Examples:
    echo   create-update.bat feature/new-ui
    echo   create-update.bat feature/new-ui 1.1.0
    echo   create-update.bat feature/new-ui 1.1.0 "New UI improvements"
    echo.
    pause
    exit /b 1
)

set BRANCH_NAME=%~1
set VERSION=%~2
set DESCRIPTION=%~3

if "%VERSION%"=="" set VERSION=auto
if "%DESCRIPTION%"=="" set DESCRIPTION=Update from branch: %BRANCH_NAME%

echo Creating update package...
echo Branch: %BRANCH_NAME%
echo Version: %VERSION%
echo Description: %DESCRIPTION%
echo.

php scripts/create-update-package.php "%BRANCH_NAME%" "%VERSION%" "%DESCRIPTION%"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ✅ Update package created successfully!
    echo.
    echo Next steps:
    echo 1. Go to Admin Panel → System Updates
    echo 2. Upload the generated ZIP file
    echo 3. Apply the update
    echo.
) else (
    echo.
    echo ❌ Failed to create update package!
    echo Please check the error messages above.
    echo.
)

pause
