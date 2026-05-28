#!/bin/bash
# Deploy script for production server (cPanel)
# Run: cd /home/u671917614/public_html/IUG && bash DEPLOY_STEPS.sh

set -e

echo "=== IUG Deploy Started ==="

# 1. Git pull latest
echo "Fetching latest code..."
git fetch origin
git reset --hard origin/main

# 2. Composer install
echo "Installing dependencies..."
HOME=/home/u671917614 composer install --no-dev --optimize-autoloader

# 3. Cache clear
echo "Clearing cache..."
php artisan config:cache
php artisan view:cache
php artisan cache:clear

# 4. Migrations
echo "Running migrations..."
php artisan migrate --force

# 5. Seeders (optional, only first time)
if [ ! -f .deployed ]; then
  echo "First deploy detected. Running seeders..."
  php artisan db:seed --class=DatabaseSeeder
  touch .deployed
fi

# 6. Storage link
echo "Creating storage link..."
php artisan storage:link || true

echo "=== Deploy Complete ==="
echo "Admin: admin@institutoulyssesguimaraes.com.br / Iug@2026Adm!"
echo "Login at: https://institutoulyssesguimaraes.com.br/IUG/admin/login"
