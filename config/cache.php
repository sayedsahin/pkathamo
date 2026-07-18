<?php

declare(strict_types=1);

return [
    'driver' => env('CACHE_DRIVER', 'apcu'),
    'path' => STORAGE_PATH . '/cache/file-cache',
    'prefix' => 'pkathamo:cache:'
];
