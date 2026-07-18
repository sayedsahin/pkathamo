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
        'username' => env('REDIS_USERNAME', null),
        'password' => env('REDIS_PASSWORD', null),
        'db' => env('REDIS_DB', 0),
        'cache_db' => env('REDIS_CACHE_DB', 1),
        'rate_limit_db' => env('REDIS_RATE_LIMIT_DB', 2),
        'prefix' => env('CACHE_PREFIX', 'app_cache:'),
        'timeout' => env('REDIS_TIMEOUT', 2.0),
        'read_timeout' => env('REDIS_READ_TIMEOUT', 2.0),
    ],
    'memcached' => [
        'persistent_id' => env('MEMCACHED_PERSISTENT_ID', 'pkathamo'),
        'connect_timeout' => env('MEMCACHED_CONNECT_TIMEOUT', 2000),

        'servers' => [
            [
                'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                'port' => (int) env('MEMCACHED_PORT', 11211),
                'weight' => (int) env('MEMCACHED_WEIGHT', 0),
            ],
            // [
            //     'host' => env('MEMCACHED_HOST_2', '127.0.0.1'),
            //     'port' => (int) env('MEMCACHED_PORT_2', 11211),
            //     'weight' => (int) env('MEMCACHED_WEIGHT_2', 50),
            // ],
        ],
    ],
];
