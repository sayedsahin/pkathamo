<?php

declare(strict_types=1);

namespace App\Supports\RateLimitDriver;

use App\Supports\RateLimitResult;
use Redis;
use RedisException;
use RuntimeException;

final class RedisDriver implements RateLimitDriverInterface
{
    private const HIT_SCRIPT = <<<'LUA'
local current = redis.call('INCR', KEYS[1])

if current == 1 then
    redis.call('EXPIRE', KEYS[1], ARGV[1])
end

local ttl = redis.call('TTL', KEYS[1])

if ttl < 1 then
    redis.call('EXPIRE', KEYS[1], ARGV[1])
    ttl = tonumber(ARGV[1])
end

return {current, ttl}
LUA;

    private Redis $redis;

    public function __construct()
    {
        if (!class_exists(Redis::class)) {
            throw new RuntimeException(
                'PHP Redis extension is not installed.'
            );
        }

        $this->redis = new Redis();

        $host = (string) config(
            'rate_limit.redis.host',
            '127.0.0.1'
        );
        $port = (int) config(
            'rate_limit.redis.port',
            6379
        );
        $timeout = (float) config(
            'rate_limit.redis.timeout',
            2.0
        );
        $readTimeout = (float) config(
            'rate_limit.redis.read_timeout',
            2.0
        );

        try {
            if (!$this->redis->connect($host, $port, $timeout)) {
                throw new RuntimeException(
                    "Unable to connect to Redis at {$host}:{$port}."
                );
            }

            if (defined('Redis::OPT_READ_TIMEOUT')) {
                $this->redis->setOption(
                    Redis::OPT_READ_TIMEOUT,
                    $readTimeout
                );
            }

            $username = config('rate_limit.redis.username');
            $password = config('rate_limit.redis.password');

            if ($password !== null && $password !== '') {
                $credentials = (
                    $username !== null
                    && $username !== ''
                )
                    ? [(string) $username, (string) $password]
                    : (string) $password;

                if (!$this->redis->auth($credentials)) {
                    throw new RuntimeException(
                        'Redis authentication failed.'
                    );
                }
            }

            $database = (int) config(
                'rate_limit.redis.database',
                0
            );

            if (!$this->redis->select($database)) {
                throw new RuntimeException(
                    "Unable to select Redis database {$database}."
                );
            }
        } catch (RedisException $exception) {
            throw new RuntimeException(
                'Redis connection failed: '
                . $exception->getMessage(),
                0,
                $exception
            );
        }
    }

    public function hit(
        string $key,
        int $maxAttempts,
        int $windowSeconds
    ): RateLimitResult {
        try {
            $result = $this->redis->eval(
                self::HIT_SCRIPT,
                [$key, (string) $windowSeconds],
                1
            );
        } catch (RedisException $exception) {
            throw new RuntimeException(
                'Redis rate-limit operation failed: '
                . $exception->getMessage(),
                0,
                $exception
            );
        }

        if (
            !is_array($result)
            || count($result) !== 2
            || !is_numeric($result[0])
            || !is_numeric($result[1])
        ) {
            throw new RuntimeException(
                'Redis returned an invalid rate-limit result.'
            );
        }

        $attempts = (int) $result[0];
        $ttl = max(1, (int) $result[1]);
        $now = time();

        return RateLimitResult::fromCounter(
            $attempts,
            $maxAttempts,
            $now + $ttl,
            $now
        );
    }

    public function clear(string $key): void
    {
        try {
            $this->redis->del($key);
        } catch (RedisException $exception) {
            throw new RuntimeException(
                'Unable to clear Redis rate-limit counter.',
                0,
                $exception
            );
        }
    }
}
