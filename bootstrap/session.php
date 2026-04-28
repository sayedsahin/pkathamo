<?php

use App\Systems\Session\Drivers\AsyncSession;
use App\Systems\Session\Drivers\NativeSession;
use App\Systems\Session\Session;

$config = require ROOT_PATH . '/config/session.php';

switch ($config['driver']) {
    case 'native':
        $driver = new NativeSession($config);
        break;

    case 'revolt':
        $driver = new AsyncSession($config);
        break;

    default:
        throw new RuntimeException('Invalid session driver');
}

Session::setDriver($driver);
// when request complete use NullSession in your controller to avoid session blocking. Session::setDriver(new NullSession());
