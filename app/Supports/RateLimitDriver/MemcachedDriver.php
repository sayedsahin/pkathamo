<?php

namespace App\Supports\RateLimitDriver;

final class MemcachedDriver implements RateLimitDriverInterface
{
    private \Memcached $mem;

    public function __construct()
    {
        $this->mem = new \Memcached();
        $this->mem->addServer('127.0.0.1', 11211);
    }

    public function hit(string $key, int $max, int $window): bool
    {
        $bucket = $this->mem->get($key);

        if (!$bucket) {
            $this->mem->set($key, [
                'count' => 1,
                'expires' => time() + $window,
            ], $window);
            return true;
        }

        if ($bucket['count'] >= $max) {
            return false;
        }

        $bucket['count']++;
        $this->mem->set($key, $bucket, $bucket['expires'] - time());
        return true;
    }
}
