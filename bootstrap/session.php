<?php

use App\Systems\Session\NativeSession;
use App\Systems\Session\Session;

$config = require __DIR__ . '/../config/session.php';

switch ($config['driver']) {
    case 'native':
        $driver = new NativeSession($config);
        break;

    case 'redis':
        // $driver = new RedisSession($config);
        break;

    case 'swoole':
        // $driver = new SwooleSession($config);
        break;

    default:
        throw new RuntimeException('Invalid session driver');
}

Session::setDriver($driver);
