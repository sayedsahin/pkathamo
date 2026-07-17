<?php
declare(strict_types=1);

namespace App\Systems\Cache\Drivers;

use App\Systems\Cache\CacheInterface;

final class FileCache implements CacheInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = rtrim($path, '/');

        if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
            throw new \RuntimeException(
                "Unable to create cache directory: {$path}"
            );
        }

        if (!is_writable($path)) {
            throw new \RuntimeException(
                "Cache directory is not writable: {$path}"
            );
        }

        $this->path = $path;
    }

    private function file(string $key): string
    {
        return $this->path . '/' . md5($key) . '.cache';
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->file($key);

        if (!file_exists($file)) {
            return $default;
        }

        $data = unserialize(file_get_contents($file));

        if ($data['expires'] !== 0 && $data['expires'] < time()) {
            unlink($file);
            return $default;
        }

        return $data['value'];
    }

    public function put(string $key, mixed $value, int $ttl = 0): void
    {
        $expires = $ttl > 0 ? time() + $ttl : 0;

        $data = serialize([
            'value' => $value,
            'expires' => $expires,
        ]);

        file_put_contents($this->file($key), $data, LOCK_EX);
    }

    public function has(string $key): bool
    {
        $missing = new \stdClass();

        return $this->get($key, $missing) !== $missing;
    }

    public function forget(string $key): void
    {
        $file = $this->file($key);

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function flush(): void
    {
        foreach (glob($this->path . '/*.cache') as $file) {
            unlink($file);
        }
    }
}