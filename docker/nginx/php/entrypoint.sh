#!/bin/bash

# Initially when the container is first built, the files will be owned by the correct user
# But if files are created by the host machine, they might have different permissions
echo "🔧 Starting PHP-FPM with developer user..."

# We don't need to change permissions here because:
# 1. The user in the container has the same UID as the host user
# 2. The volumes are mounted with the correct permissions

echo "🚀 Starting PHP-FPM..."
exec "$@"
