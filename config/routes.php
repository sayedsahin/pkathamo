<?php

// Account Route

use App\Controllers\AuthController;

$route->get('/', [AuthController::class, 'index']);
$route->get('/login', [AuthController::class, 'login']);
$route->get('/logout', [AuthController::class, 'logout']);