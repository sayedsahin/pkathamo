<?php

namespace App\Middlewares;

use Amp\Future;
use App\Supports\RateLimiter;

final class AsyncRateLimit implements MiddlewareInterface
{
    public function handle(): void
    {
        RateLimiter::enableAsync();

        $future = RateLimiter::hit(
            $this->key(),
            60,
            60
        );

        if (!$future instanceof Future) {
            throw new \RuntimeException('Expected async result');
        }

        if (!$future->await()) {
            http_response_code(429);
            echo 'Too many requests';
            exit;
        }
    }

    private function key(): string
    {
        return is_api_request()
            ? 'api:' . sha1($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')
            : 'web:' . sha1($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }
}
