#!/bin/bash
# This script can be run manually when needed to fix permissions
# After running artisan commands that create files

# Check if we have the correct paths
if [ -d "/var/www/storage" ] && [ -d "/var/www/bootstrap/cache" ]; then
    echo "🔧 Fixing Laravel permissions..."
    find /var/www/storage -type d -exec chmod 775 {} \;
    find /var/www/storage -type f -exec chmod 664 {} \;
    find /var/www/bootstrap/cache -type d -exec chmod 775 {} \;
    find /var/www/bootstrap/cache -type f -exec chmod 664 {} \;
    echo " Permissions fixed successfully!"
else
    echo " Laravel directories not found. Make sure you're in the correct directory."
fi
