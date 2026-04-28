<?php

use App\Controllers\AuthController;
use App\Controllers\ApiAuthController;
use App\Controllers\HomeController;
use App\Middlewares\Guest;

// Example-Router: $route->get('/', ['ClassName::class', 'method', [Middleware::class]]);

$route->get('/', [HomeController::class, 'index']);
$route->get('/login', [AuthController::class, 'login', [Guest::class]]);
$route->post('/login', [AuthController::class, 'loginProcess']);
$route->get('/register', [AuthController::class, 'registration']);
$route->post('/register', [AuthController::class, 'registrationProcess']);
$route->get('/logout', [AuthController::class, 'logout']);

// API Routes (Token-based auth for React/Vue)
$route->post('/api/auth/login', [ApiAuthController::class, 'login']);
$route->post('/api/auth/register', [ApiAuthController::class, 'register']);
$route->post('/api/auth/forgot', [ApiAuthController::class, 'forgot']);
$route->get('/api/auth/verify/{token}', [ApiAuthController::class, 'verify']);
$route->post('/api/auth/logout', [ApiAuthController::class, 'logout']);
$route->get('/api/auth/profile', [ApiAuthController::class, 'profile']);