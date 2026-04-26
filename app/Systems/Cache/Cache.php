<?php
declare(strict_types=1);

namespace App\Systems\Cache;

final class Cache
{
    private static CacheInterface $driver;

    public static function setDriver(CacheInterface $driver): void
    {
        self::$driver = $driver;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$driver->get($key, $default);
    }

    public static function put(string $key, mixed $value, int $ttl = 0): void
    {
        self::$driver->put($key, $value, $ttl);
    }

    public static function has(string $key): bool
    {
        return self::$driver->has($key);
    }

    public static function forget(string $key): void
    {
        self::$driver->forget($key);
    }

    public static function flush(): void
    {
        self::$driver->flush();
    }

    public static function remember(string $key, int $ttl, callable $callback): mixed
    {
        if (self::has($key)) {
            return self::get($key);
        }

        $value = $callback();

        self::put($key, $value, $ttl);

        return $value;
    }
}