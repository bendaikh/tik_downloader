#!/bin/bash

# TikTok Downloader - Update Creator Script
# Usage: ./scripts/create-update.sh <branch-name> [version] [description]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if branch name is provided
if [ $# -eq 0 ]; then
    echo "========================================"
    echo "   TikTok Downloader - Update Creator"
    echo "========================================"
    echo ""
    echo "Usage: $0 <branch-name> [version] [description]"
    echo ""
    echo "Examples:"
    echo "  $0 feature/new-ui"
    echo "  $0 feature/new-ui 1.1.0"
    echo "  $0 feature/new-ui 1.1.0 \"New UI improvements\""
    echo ""
    exit 1
fi

BRANCH_NAME="$1"
VERSION="${2:-auto}"
DESCRIPTION="${3:-Update from branch: $BRANCH_NAME}"

echo "========================================"
echo "   TikTok Downloader - Update Creator"
echo "========================================"
echo ""

print_status "Creating update package..."
echo "Branch: $BRANCH_NAME"
echo "Version: $VERSION"
echo "Description: $DESCRIPTION"
echo ""

# Check if PHP is available
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed or not in PATH"
    exit 1
fi

# Check if we're in the project root
if [ ! -f "artisan" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

# Run the PHP script
if php scripts/create-update-package.php "$BRANCH_NAME" "$VERSION" "$DESCRIPTION"; then
    echo ""
    print_success "Update package created successfully!"
    echo ""
    echo "Next steps:"
    echo "1. Go to Admin Panel → System Updates"
    echo "2. Upload the generated ZIP file"
    echo "3. Apply the update"
    echo ""
else
    echo ""
    print_error "Failed to create update package!"
    echo "Please check the error messages above."
    echo ""
    exit 1
fi
