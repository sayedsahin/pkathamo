<?php

return [
    'web' => [
        \App\Middlewares\WebHeaders::class,
        // \App\Middlewares\RateLimit::class,
        \App\Middlewares\Csrf::class,
        \App\Middlewares\SessionStart::class,
        \App\Middlewares\RememberMe::class,
        
    ],


    'api' => [
        \App\Middlewares\ApiHeaders::class,
        \App\Middlewares\RateLimit::class,
        // \App\Middlewares\AsyncRateLimit::class,
        \App\Middlewares\BearerAuth::class,
    ]
];
