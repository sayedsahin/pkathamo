<?php

use App\Systems\Container;

// $config = require ROOT_PATH . '/config/container.php';

$container = new Container();

/*
|--------------------------------------------------------------------------
| Register Singletons
|--------------------------------------------------------------------------
*/
foreach (config('container.singletons', []) as $class) {
    $container->singleton($class);
}

/*
|--------------------------------------------------------------------------
| Register Bindings
|--------------------------------------------------------------------------
*/
foreach (config('container.bindings', []) as $abstract => $concrete) {
    $container->bind($abstract, $concrete);
}

return $container;
