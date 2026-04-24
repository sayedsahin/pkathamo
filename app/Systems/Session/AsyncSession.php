<?php

namespace App\Systems\Session;

final class AsyncSession implements SessionInterface
{
    /**
     * Per-request session data
     * (Injected at request start)
     */
    private array $store = [];

    public function start(): void
    {
        // no-op
        // async session does not auto-start
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->store[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->store[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($this->store[$key]);
    }

    public function flush(): void
    {
        $this->store = [];
    }

    public function regenerate(): void
    {
        // no-op (no session id)
    }

    public function destroy(): void
    {
        $this->store = [];
    }
}

