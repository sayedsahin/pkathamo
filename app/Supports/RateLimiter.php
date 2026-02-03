<?php
namespace App\Supports;

use App\Supports\RateLimitDriver\ApcuDriver;
use App\Supports\RateLimitDriver\AsyncRedisDriver;
use App\Supports\RateLimitDriver\MemcachedDriver;
use App\Supports\RateLimitDriver\RateLimitDriverInterface;
use App\Supports\RateLimitDriver\RedisDriver;

final class RateLimiter
{
    private static ?RateLimitDriverInterface $driver = null;
    private static bool $async = false;

    public static function enableAsync(): void
    {
        self::$async = true;
    }

    public static function hit(string $key, int $max, int $window)
    {
        $driver = self::driver();

        if (self::$async) {
            if (!method_exists($driver, 'hitAsync')) {
                throw new \RuntimeException('Async driver required');
            }

            return $driver->hitAsync($key, $max, $window);
        }

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
        return match ($_ENV['RATE_LIMIT_STORE'] ?? 'auto') {
            'apcu'        => new ApcuDriver(),
            'memcached'   => new MemcachedDriver(),
            'redis'       => new RedisDriver(),
            'redis-async' => new AsyncRedisDriver(),
            default       => self::auto(),
        };
    }

    private static function auto(): RateLimitDriverInterface
    {
        if (class_exists(\Amp\Redis\RedisClient::class)) {
            return new AsyncRedisDriver();
        }

        if (class_exists(\Redis::class)) {
            return new RedisDriver();
        }

        if (class_exists(\Memcached::class)) {
            return new MemcachedDriver();
        }

        return new ApcuDriver();
    }
}



