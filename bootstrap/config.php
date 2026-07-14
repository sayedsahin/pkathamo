<?php

declare(strict_types=1);

use App\Systems\Config\Config;
use App\Systems\Config\ConfigLoader;


$cacheFile = STORAGE_PATH . '/cache/config.php';

if (is_file($cacheFile)) {
    Config::load(ConfigLoader::loadFromCache($cacheFile));
} else {
    require ROOT_PATH . '/bootstrap/env.php';

    $items = ConfigLoader::load(ROOT_PATH . '/config');
    ConfigLoader::writeCache($cacheFile, $items);

    Config::load($items);
}

date_default_timezone_set((string) config('app.timezone', 'UTC'));
define('BASE_URL', (string) config('app.url', 'http://localhost'));

