<?php

// Konfigurasi khusus untuk Vercel
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

// Set SQLite database ke folder /tmp agar Vercel bisa menulis
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=/tmp/database.sqlite');

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

try {
    // Otomatis buat database dan isi data jika belum ada di sesi Vercel ini (untuk simulasi pameran)
    if (!file_exists('/tmp/database.sqlite')) {
        touch('/tmp/database.sqlite');
        
        $consoleKernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $consoleKernel->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true
        ]);
    }

    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $request = \Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    
    // Jika response memiliki exception, kita cetak paksa!
    if (isset($response->exception) && $response->exception) {
        echo "<h1>Exception Ditemukan (Bypass)</h1>";
        echo "<p><strong>Error:</strong> " . $response->exception->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $response->exception->getFile() . ":" . $response->exception->getLine() . "</p>";
        echo "<pre>" . $response->exception->getTraceAsString() . "</pre>";
        die();
    }

    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo "<h1>Error Fatal (Try Catch)</h1>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    die();
}
