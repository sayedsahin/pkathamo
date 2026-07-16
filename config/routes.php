<?php

use App\Controllers\AuthController;
use App\Controllers\ApiAuthController;
use App\Controllers\HomeController;
use App\Middlewares\Authenticated;
use App\Middlewares\BearerAuth;
use App\Middlewares\Guest;
use App\Middlewares\RateLimit;
use App\Middlewares\RoleMiddleware;
use App\Middlewares\WebHeaders;

/**
 * @var FastRoute\RouteCollector $route
 */

// $route->get('/', ['ClassName::class', 'method', [Middleware::class, [RoleMiddleware::class, ['admin', 'user']]]]); //Example-Router:

$route->get('/', [HomeController::class, 'index']);
$route->get('/login', [AuthController::class, 'login', [Guest::class]]);
$route->post('/login', [AuthController::class, 'loginProcess', [Guest::class]]);
$route->get('/register', [AuthController::class, 'registration']);
$route->post('/register', [AuthController::class, 'registrationProcess']);
$route->get('/logout', [AuthController::class, 'logout', [Authenticated::class]]);

// API Routes (Token-based auth for React/Vue)
$route->get('/api/home', [HomeController::class, 'apiIndex', [
    BearerAuth::class,
    [RoleMiddleware::class, ['user']]
]]);
$route->post('/api/auth/login', [ApiAuthController::class, 'login']);
$route->post('/api/auth/register', [ApiAuthController::class, 'register']);
$route->post('/api/auth/forgot', [ApiAuthController::class, 'forgot']);
$route->get('/api/auth/verify/{token}', [ApiAuthController::class, 'verify']);
$route->post('/api/auth/logout', [ApiAuthController::class, 'logout', [BearerAuth::class]]);
$route->get('/api/auth/profile', [ApiAuthController::class, 'profile', [BearerAuth::class]]);