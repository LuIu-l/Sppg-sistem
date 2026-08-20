<?php

putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__.'/../vendor/autoload.php';
    echo "<p>Autoload success.</p>";
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "<p>Bootstrap success.</p>";
    
    // Jangan panggil handleRequest dulu, kita cek apakah bisa sampai sini
    echo "<p>App initialized.</p>";
} catch (\Throwable $e) {
    echo "<h1>Exception Terdeteksi</h1>";
    echo $e->getMessage();
}
