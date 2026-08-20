<?php

putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $request = \Illuminate\Http\Request::capture();
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request);
    
    // Jika response memiliki exception, kita cetak paksa!
    if (isset($response->exception) && $response->exception) {
        echo "<h1>Exception Ditemukan (Bypass)</h1>";
        echo "<p><strong>Error:</strong> " . $response->exception->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $response->exception->getFile() . ":" . $response->exception->getLine() . "</p>";
        echo "<pre>" . $response->exception->getTraceAsString() . "</pre>";
        die();
    }
    
    $response->setStatusCode(200);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo "<h1>Error Fatal</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>" . $e->getFile() . " : " . $e->getLine() . "</p>";
}
