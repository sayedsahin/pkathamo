<?php
declare(strict_types=1);

namespace App\Systems\Cache\Drivers;

use App\Systems\Cache\CacheInterface;
use Redis;
use RuntimeException;

final class RedisCache implements CacheInterface
{
    private ?Redis $redis = null;
    private array $config;
    private string $prefix;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->prefix = $config['prefix'] ?? 'cache:';
    }

    private function redis(): Redis
    {
        if ($this->redis !== null) {
            return $this->redis;
        }

        $redis = new Redis();

        if (!$redis->connect(
            $this->config['host'],
            (int) $this->config['port']
        )) {
            throw new RuntimeException('Redis connection failed.');
        }

        if (!empty($this->config['password'])) {
            $redis->auth($this->config['password']);
        }

            $cache_db = (int) ($this->config['cache_db'] ?? $this->config['db'] ?? 0);
            $redis->select($cache_db);

        return $this->redis = $redis;
    }

    private function key(string $key): string
    {
        return $this->prefix . $key;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->redis()->get($this->key($key));

        if ($value === false) {
            return $default;
        }

        try {
            return unserialize($value);
        } catch (\Throwable) {
            return $default;
        }
    }

    public function put(string $key, mixed $value, int $ttl = 0): void
    {
        $payload = serialize($value);
        $key = $this->key($key);

        if ($ttl > 0) {
            $this->redis()->setex($key, $ttl, $payload);
        } else {
            $this->redis()->set($key, $payload);
        }
    }

    public function has(string $key): bool
    {
        return $this->redis()->exists($this->key($key)) > 0;
    }

    public function forget(string $key): void
    {
        $this->redis()->del($this->key($key));
    }

    public function flush(): void
    {
        // ⚠️ Safe flush (prefix-based, not full DB wipe)

        $redis = $this->redis();
        $prefix = $this->prefix;

        $iterator = null;

        while ($keys = $redis->scan($iterator, $prefix . '*', 100)) {
            if (!empty($keys)) {
                $redis->del($keys);
            }
        }
    }
}