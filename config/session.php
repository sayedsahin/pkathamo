<?php

declare(strict_types=1);

return [
    'driver' => env('SESSION_DRIVER', 'native'),
    'lifetime' => env('SESSION_LIFETIME', 7200),
    'secure' => env('SESSION_SECURE', true),
    'samesite' => env('SESSION_SAMESITE', 'Lax'),
];
