<?php

define('LARAVEL_START', microtime(true));

/**
 * Vercel Serverless Entry Point for Laravel
 *
 * Vercel's filesystem is read-only except for /tmp.
 * We redirect Laravel's storage to /tmp/laravel-storage so views can be
 * compiled, cache can be written, and sessions can be stored.
 */
$tmpStorage = '/tmp/laravel-storage';

foreach ([
    "$tmpStorage/framework/views",
    "$tmpStorage/framework/cache/data",
    "$tmpStorage/framework/sessions",
    "$tmpStorage/logs",
    "$tmpStorage/app/public",
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Maintenance mode check (uses the real storage path before override)
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Point all writable storage to /tmp so Vercel doesn't throw read-only errors
$app->useStoragePath($tmpStorage);

$app->handleRequest(Illuminate\Http\Request::capture());
