<?php

use Systems\Container;

$config = require __DIR__ . '/../config/container.php';

$container = new Container();

/*
|--------------------------------------------------------------------------
| Register Singletons
|--------------------------------------------------------------------------
*/
foreach ($config['singletons'] as $class) {
    $container->singleton($class);
}

/*
|--------------------------------------------------------------------------
| Register Bindings
|--------------------------------------------------------------------------
*/
foreach ($config['bindings'] as $abstract => $concrete) {
    $container->bind($abstract, $concrete);
}

return $container;
