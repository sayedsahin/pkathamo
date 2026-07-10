<?php

return [
    'web' => [
        \App\Middlewares\WebHeaders::class,
        // \App\Middlewares\RateLimit::class,
        \App\Middlewares\SessionStart::class,
        \App\Middlewares\RememberMe::class,
        \App\Middlewares\Csrf::class,
        
    ],


    'api' => [
        \App\Middlewares\ApiHeaders::class,
        \App\Middlewares\RateLimit::class,
        // \App\Middlewares\AsyncRateLimit::class,
        // \App\Middlewares\BearerAuth::class, //Not use globally
    ]
];
