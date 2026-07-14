<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'PK Framework'),
    'debug' => env('DEBUG_MODE', false), // false = production
    'url' => rtrim((string) env('BASE_URL', 'http://localhost'), '/'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    'trusted_proxies' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('TRUSTED_PROXIES', ''))
    ))),
];
