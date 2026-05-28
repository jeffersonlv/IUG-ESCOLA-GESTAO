<?php
set_time_limit(300);
date_default_timezone_set('UTC');

$output = [];
$errors = [];

function run($cmd, &$output, &$errors) {
    $output[] = "$ $cmd";
    $result = shell_exec("$cmd 2>&1");
    $output[] = $result;
    return $result;
}

$baseDir = dirname(dirname(__FILE__));
chdir($baseDir);

putenv('HOME=/home/u671917614');

$output[] = "=== Laravel Deploy ===";
$output[] = "Time: " . date('Y-m-d H:i:s');
$output[] = "Dir: " . getcwd();
$output[] = "";

run("git fetch origin", $output, $errors);
run("git reset --hard origin/main", $output, $errors);
$output[] = "";

if (!file_exists('.env') && file_exists('.env.example')) {
    copy('.env.example', '.env');
    $output[] = "Created .env from .env.example";
}

// Always ensure APP_KEY is set and valid
run("php artisan key:generate --force", $output, $errors);
$output[] = "";

if (file_exists('composer.json')) {
    run("HOME=/home/u671917614 composer install --no-interaction --prefer-dist --optimize-autoloader", $output, $errors);
    $output[] = "";
}

// Verify APP_KEY was set in .env
$envContent = file_get_contents('.env');
if (!preg_match('/APP_KEY=base64:/', $envContent)) {
    $output[] = "APP_KEY still empty, re-running key:generate...";
    run("php artisan key:generate --force --always", $output, $errors);
}
$output[] = "";

run("php artisan config:cache", $output, $errors);
run("php artisan view:cache", $output, $errors);
run("php artisan cache:clear", $output, $errors);

if (file_exists('database/migrations')) {
    run("php artisan migrate --force", $output, $errors);
}

run("php artisan storage:link --force", $output, $errors);

$output[] = "";
$output[] = "=== Deploy Complete ===";
$output[] = date('Y-m-d H:i:s');

header('Content-Type: text/plain; charset=utf-8');
echo implode("\n", $output);
?>
