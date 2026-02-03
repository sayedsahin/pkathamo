<?php

namespace App\Middlewares;

class ApiHeaders implements MiddlewareInterface
{
    public function handle(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        header('X-Content-Type-Options: nosniff');
    }
}

