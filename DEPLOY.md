# Deployment Guide — IUG (institutoulyssesguimaraes.com.br/IUG)

## Server Setup (cPanel, PHP 7.4, MySQL)

### 1. Initial Clone + Dependencies
```bash
cd /home/u671917614/public_html/IUG
git clone https://github.com/jeffersonlv/IUG.git .
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
```

### 2. Database Configuration
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u671917614_iug
DB_USERNAME=u671917614_iug
DB_PASSWORD=<strong-password>
```

### 3. Run Migrations + Seeders
```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

**Result:** `users` table with admin user:
- Email: `admin@institutoulyssesguimaraes.com.br`
- Password: `Iug@2026Adm!`
- Active: ✓

### 4. Storage Link
```bash
php artisan storage:link
```

Creates `public/storage` symlink → `storage/app/public` (for PDF uploads).

### 5. Permissions
```bash
chmod -R 755 storage/logs storage/framework
chmod -R 775 storage
```

### 6. .htaccess Routing
Root `.htaccess` already configured:
- `/IUG/*` → `/IUG/public/*` (rewrite rule line 16)
- `public/` must have its own `.htaccess` (Laravel default)

Verify: `https://institutoulyssesguimaraes.com.br/IUG/` loads welcome page.

---

## Deployment via deploy.php (Auto)

**Manual trigger:**
```
https://institutoulyssesguimaraes.com.br/IUG/public/deploy.php?token=IUG2k7mP9Vxq3WnL5tYzA8bRdF6jCsE1
```

**Script steps:**
1. `git fetch origin`
2. `git reset --hard origin/main`
3. `composer install --no-dev --optimize-autoloader`
4. `php artisan config:cache`
5. `php artisan view:cache`
6. `php artisan cache:clear`
7. `php artisan migrate --force`

**Cron (optional):**
```bash
0 2 * * * cd /home/u671917614/public_html/IUG && php artisan schedule:run >> /dev/null 2>&1
```

---

## Environment Variables (Production)

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...  # Already generated
APP_URL=https://institutoulyssesguimaraes.com.br/IUG

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=u671917614_iug
DB_USERNAME=u671917614_iug
DB_PASSWORD=...

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=sendmail
MAIL_FROM_ADDRESS=noreply@institutoulyssesguimaraes.com.br
```

---

## Troubleshooting

### 403 Forbidden
- Check `.htaccess` in `/public/` exists
- Run `php artisan serve` locally to test routing

### "No app key set"
```bash
php artisan key:generate --force
php artisan config:cache
```

### Database connection error
- Verify credentials in `.env`
- Run `php artisan tinker` → `DB::connection()->getPDO()` to test

### Views not compiling
```bash
php artisan view:clear
php artisan config:clear
```

### Storage not writable
```bash
php artisan storage:link
chmod -R 775 storage
```

---

## Admin Login
1. Visit: `https://institutoulyssesguimaraes.com.br/IUG/admin/login`
2. Email: `admin@institutoulyssesguimaraes.com.br`
3. Password: `Iug@2026Adm!`
4. After login: `/admin/dashboard`

---

## Next Steps

After deployment:
1. **Change admin password** → Admin dashboard
2. **Test CRUD** — Create curso, documento, message
3. **Upload PDFs** — Test storage symlink
4. **Set SiteConfig** — Textos, logo, contatos (when admin CRUD added)

---

## Logs
- Laravel: `/storage/logs/laravel.log`
- PHP: cPanel logs
- Deploy: `/public/deploy.php` writes to log (check stdout)

---

## Rollback
```bash
git reset --hard <commit-hash>
php artisan migrate:rollback
php artisan serve  # test locally first
git push origin main  # deploy when ready
```
