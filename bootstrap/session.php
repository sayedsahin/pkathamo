<?php

use App\Systems\Session\Drivers\NativeSession;
use App\Systems\Session\Drivers\NullSession;
use App\Systems\Session\Session;

$config = (array) config('session');

switch ($config['driver']) {
    case 'native':
        $driver = new NativeSession($config);
        break;

    case 'file':
        $driver = new NullSession($config); // change to FileSession
        break;
    case 'redis':
        $driver = new NullSession($config); // change to RedisSession
        break;

    default:
        throw new RuntimeException('Invalid session driver');
}

Session::setDriver($driver);
// when request complete use NullSession in your controller to avoid session blocking. Session::setDriver(new NullSession());
