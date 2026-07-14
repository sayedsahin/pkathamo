<?php

namespace App\Supports\RateLimitDriver;

final class RedisDriver implements RateLimitDriverInterface
{
    private \Redis $redis;

    public function __construct()
    {
        $redis_host = config('database.redis.host');
        $redis_port = config('database.redis.port');

        $this->redis = new \Redis();
        $this->redis->connect($redis_host, $redis_port);
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
