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

// Otomatis buat database dan isi data jika belum ada di sesi Vercel ini (untuk simulasi pameran)
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
    
    // Jalankan migrasi dan seeding secara programmatis
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->call('migrate:fresh', [
        '--seed' => true,
        '--force' => true
    ]);
}

// Lanjutkan request seperti biasa
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
