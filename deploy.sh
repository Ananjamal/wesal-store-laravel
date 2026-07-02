#!/bin/bash
# ====================================================
# Wisal Store — Hostinger Deployment Script (SSH)
# Run this from: /home/username/wisal-store/
# ====================================================

set -e

echo "▶ Starting Wisal Store deployment..."

# 1. Install PHP dependencies (no dev)
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Generate app key if not set
echo "🔑 Generating app key..."
php artisan key:generate --no-interaction --force 2>/dev/null || echo "Key already exists."

# 3. Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction

# 4. Link storage
echo "🔗 Linking storage..."
php artisan storage:link --force 2>/dev/null || echo "Storage link already exists."

# 5. Clear and cache everything
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Filament setup
echo "🎨 Upgrading Filament..."
php artisan filament:upgrade

# 7. Set permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo ""
echo "✅ Deployment complete!"
echo "   Visit: https://yourdomain.com"
echo "   Admin: https://yourdomain.com/admin"
