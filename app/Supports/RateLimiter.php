<?php
namespace App\Supports;

use App\Supports\RateLimitDriver\ApcuDriver;
use App\Supports\RateLimitDriver\MemcachedDriver;
use App\Supports\RateLimitDriver\RateLimitDriverInterface;
use App\Supports\RateLimitDriver\RedisDriver;

final class RateLimiter
{
    private static ?RateLimitDriverInterface $driver = null;

    public static function hit(string $key, int $max, int $window)
    {
        $driver = self::driver();

        return $driver->hit($key, $max, $window);
    }

    private static function driver(): RateLimitDriverInterface
    {
        if (!self::$driver) {
            self::$driver = self::resolve();
        }

        return self::$driver;
    }

    private static function resolve(): RateLimitDriverInterface
    {
        return match (config('app.rate_limit_store')) {
            'apcu'        => new ApcuDriver(),
            'memcached'   => new MemcachedDriver(),
            'redis'       => new RedisDriver(),
            default       => self::auto(),
        };
    }

    private static function auto(): RateLimitDriverInterface
    {
        if (class_exists(\Redis::class)) {
            return new RedisDriver();
        }

        if (class_exists(\Memcached::class)) {
            return new MemcachedDriver();
        }

        if (function_exists('apcu_enabled') && apcu_enabled()) {
            return new ApcuDriver();
        }

        throw new \RuntimeException('No rate-limit driver is available. Install Redis, Memcached or APCu.');
    }
}



