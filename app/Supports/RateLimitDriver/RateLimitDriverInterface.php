<?php

namespace App\Supports\RateLimitDriver;

interface RateLimitDriverInterface
{
    public function hit(string $key, int $max, int $window): bool;
}
