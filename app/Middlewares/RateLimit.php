<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Supports\RateLimiter;
use App\Supports\RateLimitResult;

final class RateLimit implements MiddlewareInterface
{
    public function handle(): void
    {
        [$key, $maxAttempts, $windowSeconds] = $this->policy();

        $result = RateLimiter::hit(
            $key,
            $maxAttempts,
            $windowSeconds
        );

        $this->sendHeaders($result);

        if (!$result->allowed()) {
            $this->reject($result);
        }
    }

    private function policy(): array
    {
        $method = strtoupper(request()->method());

        $path = '/' . trim(
            request()->path(),
            '/'
        );

        $signature = $method . ' ' . $path;

        $sensitive = config(
            'rate_limit.sensitive_routes',
            []
        );

        $routes = is_array($sensitive)
            ? ($sensitive['routes'] ?? [])
            : [];

        if (
            is_array($routes)
            && in_array($signature, $routes, true)
        ) {
            return [
                'sensitive:'
                    . $signature
                    . ':ip:'
                    . $this->ip(),

                (int) ($sensitive['max_attempts'] ?? 5),

                (int) ($sensitive['window_seconds'] ?? 60),
            ];
        }

        return is_api_request()
            ? $this->apiPolicy()
            : $this->webPolicy();
    }

    private function apiPolicy(): array
    {
        return [
            'api:ip:' . $this->ip(),
            (int) config(
                'rate_limit.api.max_attempts',
                120
            ),
            (int) config(
                'rate_limit.api.window_seconds',
                60
            ),
        ];
    }

    private function webPolicy(): array
    {
        if (isset($_SESSION['auth_user_id'])) {
            return [
                'web:user:' . (int) $_SESSION['auth_user_id'],
                (int) config(
                    'rate_limit.web.authenticated.max_attempts',
                    240
                ),
                (int) config(
                    'rate_limit.web.authenticated.window_seconds',
                    60
                ),
            ];
        }

        return [
            'web:ip:' . $this->ip(),
            (int) config(
                'rate_limit.web.guest.max_attempts',
                120
            ),
            (int) config(
                'rate_limit.web.guest.window_seconds',
                60
            ),
        ];
    }

    private function ip(): string
    {
        $ip = request()->ip();

        return is_string($ip) && $ip !== ''
            ? $ip
            : '0.0.0.0';
    }

    private function sendHeaders(RateLimitResult $result): void
    {
        if (headers_sent()) {
            return;
        }

        header('X-RateLimit-Limit: ' . $result->limit());
        header(
            'X-RateLimit-Remaining: '
            . $result->remaining()
        );
        header('X-RateLimit-Reset: ' . $result->resetAt());
    }

    private function reject(RateLimitResult $result): void
    {
        http_response_code(429);

        if (!headers_sent()) {
            header(
                'Retry-After: '
                . max(1, $result->retryAfter())
            );
        }

        if (is_api_request()) {
            if (!headers_sent()) {
                header(
                    'Content-Type: application/json; charset=utf-8'
                );
            }

            echo json_encode(
                [
                    'error' => 'Too many requests',
                    'retry_after' => max(
                        1,
                        $result->retryAfter()
                    ),
                ],
                JSON_UNESCAPED_SLASHES
            );
        } else {
            echo 'Too many requests. Please try again later.';
        }

        exit;
    }
}
