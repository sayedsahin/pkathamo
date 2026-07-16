<?php

namespace App\Middlewares;

use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;

class ApiHeaders implements MiddlewareInterface
{
    public function handle(): ?Response
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');

        return null;
    }
}

