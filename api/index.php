<?php

// Konfigurasi khusus untuk Vercel (Read-Only Filesystem)
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');

// Kita gunakan cookie session dan array cache untuk serverless
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

require __DIR__ . '/../public/index.php';
