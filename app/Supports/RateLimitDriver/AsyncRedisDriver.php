<?php

namespace App\Supports\RateLimitDriver;

use Amp\Redis\RedisClient;
use Amp\Redis\RedisConfig;
use Amp\Future;

final class AsyncRedisDriver implements RateLimitDriverInterface
{
    private RedisClient $redis;

    public function __construct()
    {
        $redis_uri = 'redis://' . ($_ENV['REDIS_HOST'] ?? '127.0.0.1') . ':' . ($_ENV['REDIS_PORT'] ?? 6379);
        $config = RedisConfig::fromUri($redis_uri);

        $this->redis = new RedisClient($config);
    }

    /**
     * ASYNC version
     * Returns Future<bool>
     */
    public function hitAsync(string $key, int $max, int $window): Future
    {
        return \Amp\async(function () use ($key, $max, $window) {
            $current = yield $this->redis->increment($key);

            if ($current === 1) {
                yield $this->redis->expire($key, $window);
            }

            return $current <= $max;
        });
    }

    /**
     * Sync interface fallback (NOT recommended)
     */
    public function hit(string $key, int $max, int $window): bool
    {
        throw new \RuntimeException(
            'AsyncRedisDriver cannot be used in sync context'
        );
    }
}

