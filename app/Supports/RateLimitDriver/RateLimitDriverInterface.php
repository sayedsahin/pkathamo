<?php

declare(strict_types=1);

namespace App\Supports\RateLimitDriver;

use App\Supports\RateLimitResult;

interface RateLimitDriverInterface
{
    public function hit(
        string $key,
        int $maxAttempts,
        int $windowSeconds
    ): RateLimitResult;

    public function clear(string $key): void;
}
