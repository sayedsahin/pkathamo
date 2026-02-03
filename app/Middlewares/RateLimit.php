<?php
namespace App\Middlewares;

use App\Supports\RateLimiter;

class RateLimit implements MiddlewareInterface
{
    protected int $apiIpLimit    = 30;
    protected int $webIpLimit    = 30;

    protected int $apiTokenLimit = 60;
    protected int $webUserLimit  = 120;

    public function handle(): void
    {
        $isApi = is_api_request();

        [$key, $max, $window] = $isApi
            ? $this->apiPolicy()
            : $this->webPolicy();

        if (!RateLimiter::hit($key, $max, $window)) {
            $this->reject($window);
        }
    }

    private function apiPolicy(): array
    {
        $token = $this->bearerToken();

        if ($token) {
            return ['api:' . sha1($token), $this->apiTokenLimit, 60]; // 60 seconds
        }

        return ['api:ip:' . $this->ip(), $this->apiIpLimit, 60];
    }

    private function webPolicy(): array
    {
        if (isset($_SESSION['id'])) {
            return ['web:user:' . $_SESSION['id'], $this->webUserLimit, 60];
        }

        return ['web:ip:' . $this->ip(), $this->webIpLimit, 60];
    }

    private function bearerToken(): ?string
    {
        $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return str_starts_with($h, 'Bearer ') ? substr($h, 7) : null;
    }

    private function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function reject(int $window): void
    {
        http_response_code(429);

        if (is_api_request()) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Too many requests',
                'retry_after' => $window,
            ]);
        } else {
            echo 'Too many requests. Please try again later.';
        }

        exit;
    }
}

