<?php

use App\Systems\Cache\Cache;
use App\Systems\Cache\Drivers\RedisCache;
use App\Systems\Cache\Drivers\ArrayCache;
use App\Systems\Cache\Drivers\FileCache;

$config = require ROOT_PATH . '/config/cache.php';

$driver = match ($config['driver']) {
    'array' => new ArrayCache(),

    'file'  => new FileCache($config['path']),

    'redis' => new RedisCache($config['redis']),

    default => throw new RuntimeException(
        'Unsupported cache driver: ' . $config['driver']
    ),
};

Cache::setDriver($driver);