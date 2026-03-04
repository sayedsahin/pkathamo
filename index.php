<?php
declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Bootstrap Paths & Autoload
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/config/path.php';
require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load Cached ENV (FAST)
|--------------------------------------------------------------------------
*/
if (is_file(__DIR__ . '/storage/cache/env.php')) {
    foreach (require __DIR__ . '/storage/cache/env.php' as $k => $v) {
        $_ENV[$k] = $v;
    }
} else {
    require __DIR__ . '/bin/cache-env.php';
}
/*
|--------------------------------------------------------------------------
| Global Config
|--------------------------------------------------------------------------
*/
require __DIR__ . '/config/config.php';


/*
|--------------------------------------------------------------------------
| Detect Request Type (API or WEB)
|--------------------------------------------------------------------------
*/
$isApi = is_api_request();

if (!$isApi) {
    require __DIR__ . '/bootstrap/session.php';
}

/*
|--------------------------------------------------------------------------
| Middleware Kernel (Headers, Auth, CSRF, etc.)
|--------------------------------------------------------------------------
*/
$kernel = new \App\Systems\MiddlewareKernel();

$middlewares = require __DIR__ . '/config/middleware.php';

$kernel->web($middlewares['web']);
$kernel->api($middlewares['api']);

$kernel->run($isApi);

/*
|--------------------------------------------------------------------------
| Bootstrap Container (After Middleware)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/bootstrap/container.php';



/*
|--------------------------------------------------------------------------
| Route Dispatch
|--------------------------------------------------------------------------
*/
require __DIR__ . '/app/Systems/FastRoute.php';
