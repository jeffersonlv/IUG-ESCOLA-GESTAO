<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    // Simulate request to /admin/login
    $request = \Illuminate\Http\Request::create('/admin/login', 'GET');
    $response = $kernel->handle($request);

    echo "Status: " . $response->status() . "\n";
    echo "Content-Type: " . $response->headers->get('content-type') . "\n\n";
    echo "Body:\n";
    echo $response->getContent();

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Trace:\n";
    echo $e->getTraceAsString();
}
