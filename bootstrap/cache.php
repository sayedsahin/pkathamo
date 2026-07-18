<?php

declare(strict_types=1);

use App\Systems\Cache\Cache;
use App\Systems\Cache\CacheInterface;
use App\Systems\Cache\Drivers\ApcuCache;
use App\Systems\Cache\Drivers\ArrayCache;
use App\Systems\Cache\Drivers\FileCache;
use App\Systems\Cache\Drivers\MemcachedCache;
use App\Systems\Cache\Drivers\RedisCache;

$config = (array) config('cache');

Cache::setResolver(
    static function () use ($config): CacheInterface {
        $driver = $config['driver'];

        return match ($driver) {
            'array' => new ArrayCache(),

            'apcu' => new ApcuCache($config['prefix']),

            'file' => new FileCache($config['path']),

            'redis' => new RedisCache(config('database.redis', [])),

            'memcached' => new MemcachedCache(config('database.memcached'), $config['prefix']),

            default => throw new \RuntimeException(
                'Unsupported cache driver: ' . $driver
            ),
        };
    }
);