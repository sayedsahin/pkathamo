<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Systems\Middleware\MiddlewareKernel;
use App\Systems\Response;

abstract class Controller
{
    /**
     * Execute one middleware immediately.
     */
    protected function middleware(string|array $middleware): void
    {
        $response = MiddlewareKernel::handle([
            $middleware,
        ]);

        if ($response instanceof Response) {
            $response->send();
            exit;
        }
    }

    /**
     * Execute multiple middleware immediately.
     */
    protected function middlewares(array $middlewares): void
    {
        $response = MiddlewareKernel::handle(
            $middlewares
        );

        if ($response instanceof Response) {
            $response->send();
            exit;
        }
    }
}