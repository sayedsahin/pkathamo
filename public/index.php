<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Bootstrap Paths & Autoload
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/../config/path.php';
require_once ROOT_PATH . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load Cached ENV (FAST)
|--------------------------------------------------------------------------
*/
if (is_file(ROOT_PATH . '/storage/cache/env.php')) {
    foreach (require ROOT_PATH . '/storage/cache/env.php' as $k => $v) {
        $_ENV[$k] = $v;
    }
} else {
    require ROOT_PATH . '/bin/cache-env.php';
}
/*
|--------------------------------------------------------------------------
| Global Config
|--------------------------------------------------------------------------
*/
require ROOT_PATH . '/config/config.php';

// request remove starting for async
// App\Supports\RequestContext::clear();

/*
|--------------------------------------------------------------------------
| Detect Request Type (API or WEB)
|--------------------------------------------------------------------------
*/
$isApi = is_api_request();

if (!$isApi) {
    require ROOT_PATH . '/bootstrap/session.php';
}

/*
|--------------------------------------------------------------------------
| Cache Setup
|--------------------------------------------------------------------------
*/
require ROOT_PATH . '/bootstrap/cache.php';


// Auth should be bootstrapped after session
require ROOT_PATH . '/bootstrap/auth.php';

/*
|--------------------------------------------------------------------------
| Bootstrap Container (After Middleware)
|--------------------------------------------------------------------------
*/
require ROOT_PATH . '/bootstrap/container.php';


/*
|--------------------------------------------------------------------------
| Middleware Kernel (Headers, Auth, CSRF, etc.)
|--------------------------------------------------------------------------
*/
$kernel = new \App\Systems\MiddlewareKernel();

$middlewares = require ROOT_PATH . '/config/middleware.php';

$kernel->web($middlewares['web']);
$kernel->api($middlewares['api']);

$kernel->run($isApi);

/*
|--------------------------------------------------------------------------
| Route Dispatch
|--------------------------------------------------------------------------
*/
require ROOT_PATH . '/app/Systems/FastRoute.php';



// request remove after ending
// App\Supports\RequestContext::clear();
