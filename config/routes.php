<?php

// Account Route

use App\Controllers\AccountController;

$route->get('/', [AccountController::class, 'index']);
$route->post('/login', [AccountController::class, 'login']);
$route->get('/logout', [AccountController::class, 'logout']);
