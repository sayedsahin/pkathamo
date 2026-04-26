<?php
return [
    'driver' => $_ENV['CACHE_DRIVER'],
    'path'   => STORAGE_PATH . '/cache/file',

    'redis' => [
        'host' => $_ENV['REDIS_HOST'],
        'port' => $_ENV['REDIS_PORT'],
        'password' => $_ENV['REDIS_PASSWORD'],
        'db' => $_ENV['REDIS_DB'],
        'prefix' => $_ENV['CACHE_PREFIX'],
    ],
];