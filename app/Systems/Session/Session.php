<?php
namespace App\Systems\Session;

final class Session
{
    private static SessionInterface $driver;

    public static function setDriver(SessionInterface $driver): void
    {
        self::$driver = $driver;
    }

    private static function driver(): SessionInterface
    {
        if (!isset(self::$driver)) {
            throw new \RuntimeException('Session driver not initialized');
        }
        return self::$driver;
    }

    public static function start(): void
    {
        self::driver()->start();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::driver()->get($key, $default);
    }

    public static function set(string $key, mixed $value): void
    {
        self::driver()->set($key, $value);
    }

    public static function forget(string $key): void
    {
        self::driver()->forget($key);
    }

    public static function regenerate(): void
    {
        self::driver()->regenerate();
    }

    public static function destroy(): void
    {
        self::driver()->destroy();
    }
}
