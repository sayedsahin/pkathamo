<?php

use App\Systems\Session\Drivers\NativeSession;
use App\Systems\Session\Drivers\NullSession;
use App\Systems\Session\Session;

$config = (array) config('session');

switch ($config['driver']) {
    case 'native':
        $driver = new NativeSession($config);
        break;

    case 'null':
        $driver = new NullSession($config); // change to FileSession
        break;

    default:
        throw new RuntimeException('Invalid session driver');
}

Session::setDriver($driver);
