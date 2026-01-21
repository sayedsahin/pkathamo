<?php

require_once __DIR__.'/vendor/autoload.php';


if (file_exists('storage/cache/env.php')) {
    $env = include 'storage/cache/env.php';
    foreach ($env as $k => $v) {
        $_ENV[$k] = $v;
    }
} else {
    include __DIR__ . '/bin/cache-env.php';
}



require_once __DIR__.'/config/config.php';

require __DIR__.'/bootstrap/container.php';

include_once __DIR__.'/systems/Utility.php';
include_once __DIR__.'/systems/FastRoute.php';

// $main = new \Systems\Core();