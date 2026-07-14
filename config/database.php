<?php

declare(strict_types=1);

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_NAME', ''),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'options' => [
                'persistent' => env('DB_PERSISTENT', true),
            ],
        ],
    ],
    'redis' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
        'password' => env('REDIS_PASSWORD', null),
        'db' => env('REDIS_DB', 0),
        'cache_db' => env('REDIS_CACHE_DB', 1),
        'prefix' => env('CACHE_PREFIX', 'app_cache:'),
    ],
];
