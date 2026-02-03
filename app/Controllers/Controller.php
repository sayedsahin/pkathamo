<?php
namespace App\Controllers;

abstract class Controller
{
    /**
     * Execute a middleware immediately
     */
    protected function middleware(string $middlewareClass): void
    {
        // Safety check
        if (!class_exists($middlewareClass)) {
            throw new \RuntimeException("Middleware not found: {$middlewareClass}");
        }

        $middleware = new $middlewareClass();
        $middleware->handle();
    }

    protected function middlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $this->middleware($middleware);
        }
    }
}
