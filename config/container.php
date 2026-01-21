<?php
// config/container.php

return [

    'singletons' => [
        \Systems\Database::class,
        // \Http\Request::class,
        // \Http\Response::class,
        // \Cache\FileCache::class,
    ],

    'bindings' => [
        // \Contracts\LoggerInterface::class =>
        //     \Systems\FileLogger::class,

        // \Contracts\CacheInterface::class =>
        //     \Cache\FileCache::class,
    ],

];
