<?php
// Command: php bin/cache-routes.php
// Caches FastRoute dispatcher for production performance

require_once __DIR__ . '/../vendor/autoload.php';

$cacheFile = __DIR__ . '/../storage/cache/route.cache';

// Clear existing cache
if (file_exists($cacheFile)) {
    unlink($cacheFile);
    echo "[✓] Cleared existing route cache\n";
}

// Generate new cache by running dispatcher
$dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $route) {
    require_once __DIR__ . '/../config/routes.php';
}, [
    'cacheFile' => $cacheFile,
    'cacheDisabled' => false,  // Force cache generation
]);

if (file_exists($cacheFile)) {
    $fileSize = filesize($cacheFile);
    echo "[✓] Route cache generated successfully!\n";
    echo "    Location: $cacheFile\n";
    echo "    Size: " . number_format($fileSize, 0) . " bytes\n";

    if (php_sapi_name() === 'cli') {
        echo "\n✓ Run this before production deployment\n";
    }
} else {
    echo "[✗] Failed to generate route cache\n";
    exit(1);
}
