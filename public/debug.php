<?php
/**
 * IUG Debug Tool
 * Access via: https://institutoulyssesguimaraes.com.br/IUG/public/debug.php
 *
 * Shows system info, environment, logs, and diagnostics
 */

$output = [];

// PHP Info
$output['PHP Version'] = phpversion();
$output['PHP SAPI'] = php_sapi_name();
$output['Max Upload'] = ini_get('upload_max_filesize');
$output['Max POST'] = ini_get('post_max_size');
$output['Memory Limit'] = ini_get('memory_limit');
$output['Max Execution'] = ini_get('max_execution_time');

// Environment
$output['APP_ENV'] = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'not set';
$output['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? 'not set';
$output['Current Directory'] = getcwd();
$output['Script Directory'] = dirname(__FILE__);

// File Permissions
$storagePath = dirname(__FILE__) . '/../storage';
$output['Storage Path Exists'] = is_dir($storagePath) ? '✓' : '✗';
$output['Storage Writable'] = is_writable($storagePath) ? '✓' : '✗';
$output['Storage Logs Writable'] = is_writable($storagePath . '/logs') ? '✓' : '✗';

// Database Check
$envPath = dirname(__FILE__) . '/../.env';
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    $output['DB Host'] = $env['DB_HOST'] ?? 'not set';
    $output['DB Name'] = $env['DB_DATABASE'] ?? 'not set';

    // Try to connect
    $output['DB Connection'] = 'testing...';
    try {
        $conn = new mysqli($env['DB_HOST'] ?? '127.0.0.1', $env['DB_USERNAME'] ?? 'root', $env['DB_PASSWORD'] ?? '', $env['DB_DATABASE'] ?? '');
        if ($conn->connect_error) {
            $output['DB Connection'] = '✗ ' . $conn->connect_error;
        } else {
            $output['DB Connection'] = '✓ Connected';
            $conn->close();
        }
    } catch (Exception $e) {
        $output['DB Connection'] = '✗ ' . $e->getMessage();
    }
}

// Laravel Logs
$logsPath = dirname(__FILE__) . '/../storage/logs';
$output['Logs Path Exists'] = is_dir($logsPath) ? '✓' : '✗';

$logs = [];
if (is_dir($logsPath)) {
    $files = glob($logsPath . '/*.log');
    foreach ($files as $file) {
        $logs[basename($file)] = [
            'size' => filesize($file),
            'modified' => date('Y-m-d H:i:s', filemtime($file))
        ];
    }
}

// Try to read latest error
$latestLog = null;
$latestTime = 0;
if (is_dir($logsPath)) {
    foreach (glob($logsPath . '/*.log') as $file) {
        $time = filemtime($file);
        if ($time > $latestTime) {
            $latestTime = $time;
            $latestLog = $file;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>IUG Debug Tool</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #f5f5f5; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; border-left: 4px solid #28a745; padding-left: 10px; }
        table { background: white; border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        td { padding: 10px; border: 1px solid #ddd; }
        td:first-child { background: #f9f9f9; font-weight: bold; width: 30%; }
        .status-ok { color: green; }
        .status-error { color: red; }
        .log-entry { background: #fff; padding: 10px; margin: 5px 0; border-left: 4px solid #dc3545; font-size: 12px; white-space: pre-wrap; word-break: break-word; }
        .log-error { border-left-color: #dc3545; background: #ffe6e6; }
        .log-warning { border-left-color: #ffc107; background: #fff3cd; }
        .section { background: white; padding: 15px; margin: 20px 0; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <h1>🔧 IUG Debug Tool</h1>
    <p>System diagnostics for troubleshooting</p>

    <h2>System Information</h2>
    <div class="section">
        <table>
            <?php foreach ($output as $key => $value): ?>
                <tr>
                    <td><?= htmlspecialchars($key) ?></td>
                    <td><?= htmlspecialchars(is_array($value) ? json_encode($value) : $value) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <h2>Log Files</h2>
    <div class="section">
        <?php if ($logs): ?>
            <table>
                <tr><td>File</td><td>Size</td><td>Last Modified</td></tr>
                <?php foreach ($logs as $name => $info): ?>
                    <tr>
                        <td><?= htmlspecialchars($name) ?></td>
                        <td><?= number_format($info['size']) ?> bytes</td>
                        <td><?= htmlspecialchars($info['modified']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No log files found in <?= htmlspecialchars($logsPath) ?></p>
        <?php endif; ?>
    </div>

    <h2>Latest Error Log</h2>
    <div class="section">
        <?php if ($latestLog): ?>
            <p><strong>File:</strong> <?= htmlspecialchars(basename($latestLog)) ?></p>
            <p><strong>Size:</strong> <?= number_format(filesize($latestLog)) ?> bytes</p>
            <div style="max-height: 500px; overflow-y: auto; background: #f5f5f5; padding: 10px;">
                <pre><?php
                    $content = file_get_contents($latestLog);
                    $lines = array_reverse(explode("\n", $content));
                    foreach (array_slice($lines, 0, 100) as $line) {
                        echo htmlspecialchars($line) . "\n";
                    }
                    if (count($lines) > 100) {
                        echo "\n... (showing last 100 lines of " . count($lines) . " total) ...";
                    }
                ?></pre>
            </div>
        <?php else: ?>
            <p>No log files found.</p>
        <?php endif; ?>
    </div>

    <h2>Composer Status</h2>
    <div class="section">
        <?php
            $composerLock = dirname(__FILE__) . '/../composer.lock';
            if (file_exists($composerLock)) {
                $lockData = json_decode(file_get_contents($composerLock), true);
                echo '<p><strong>Packages installed:</strong> ' . count($lockData['packages'] ?? []) . '</p>';
                echo '<p><strong>Lock file size:</strong> ' . number_format(filesize($composerLock)) . ' bytes</p>';
                echo '<p><strong>Last modified:</strong> ' . date('Y-m-d H:i:s', filemtime($composerLock)) . '</p>';
            } else {
                echo '<p class="status-error">✗ composer.lock not found</p>';
            }
        ?>
    </div>

    <h2>Configuration Files</h2>
    <div class="section">
        <?php
            $files = ['.env', '.env.example', 'composer.json', 'artisan'];
            foreach ($files as $file) {
                $path = dirname(__FILE__) . '/../' . $file;
                $exists = file_exists($path) ? '✓' : '✗';
                $status = file_exists($path) ? 'status-ok' : 'status-error';
                echo "<p><span class='$status'>$exists</span> $file</p>";
            }
        ?>
    </div>

    <hr style="margin-top: 50px; color: #ddd;">
    <p style="color: #999; font-size: 12px;">
        Generated: <?= date('Y-m-d H:i:s') ?> UTC<br>
        Path: <?= htmlspecialchars(__FILE__) ?>
    </p>
</body>
</html>
