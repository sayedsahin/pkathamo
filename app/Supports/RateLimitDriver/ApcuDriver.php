<?php

namespace App\Supports\RateLimitDriver;


final class ApcuDriver implements RateLimitDriverInterface
{
    public function hit(string $key, int $max, int $window): bool
    {
        if (!function_exists('apcu_fetch')) {
            return true;
        }

        $now = time();
        $bucket = apcu_fetch($key, $ok);

        if (!$ok || $bucket['expires'] < $now) {
            apcu_store($key, [
                'count' => 1,
                'expires' => $now + $window,
            ], $window);
            return true;
        }

        if ($bucket['count'] >= $max) {
            return false;
        }

        $bucket['count']++;
        apcu_store($key, $bucket, $bucket['expires'] - $now);
        return true;
    }
}

