<?php

namespace App\Supports\RateLimitDriver;

final class RedisDriver implements RateLimitDriverInterface
{
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect(
            $_ENV['REDIS_HOST'] ?? '127.0.0.1',
            $_ENV['REDIS_PORT'] ?? 6379
        );
    }

    public function hit(string $key, int $max, int $window): bool
    {
        $current = $this->redis->incr($key);

        if ($current === 1) {
            $this->redis->expire($key, $window);
        }

        return $current <= $max;
    }
}
