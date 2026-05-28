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
    $output[] = "";
}

if (file_exists('composer.json')) {
    run("HOME=/home/u671917614 composer install --no-interaction --prefer-dist --optimize-autoloader", $output, $errors);
    $output[] = "";
}

run("php artisan config:cache", $output, $errors);
run("php artisan view:cache", $output, $errors);
run("php artisan cache:clear", $output, $errors);

if (file_exists('database/migrations')) {
    run("php artisan migrate --force", $output, $errors);
}

$output[] = "";
$output[] = "=== Deploy Complete ===";
$output[] = date('Y-m-d H:i:s');

header('Content-Type: text/plain; charset=utf-8');
echo implode("\n", $output);
?>
