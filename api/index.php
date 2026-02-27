<?php

define('LARAVEL_START', microtime(true));

/**
 * Vercel Serverless Entry Point for Laravel
 *
 * Vercel's filesystem is read-only except for /tmp.
 * We redirect both storage and the bootstrap cache to /tmp so that
 * Laravel can compile views, write cache, and generate the package
 * manifest without hitting read-only filesystem errors.
 */
$tmpStorage   = '/tmp/laravel-storage';
$tmpBootstrap = '/tmp/bootstrap';

// Create all writable directories Laravel needs
foreach ([
    "$tmpStorage/framework/views",
    "$tmpStorage/framework/cache/data",
    "$tmpStorage/framework/sessions",
    "$tmpStorage/logs",
    "$tmpStorage/app/public",
    "$tmpBootstrap/cache",
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Seed /tmp/bootstrap/cache with pre-generated manifests committed in the
// repo so cold starts don't have to rebuild them from scratch.
foreach (glob(__DIR__ . '/../bootstrap/cache/*.php') as $src) {
    $dst = "$tmpBootstrap/cache/" . basename($src);
    if (!file_exists($dst)) {
        copy($src, $dst);
    }
}

// Maintenance mode check (uses the real storage path before override)
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Point all writable paths to /tmp so Vercel doesn't throw read-only errors
$app->useStoragePath($tmpStorage);
$app->useBootstrapPath($tmpBootstrap);

$app->handleRequest(Illuminate\Http\Request::capture());
