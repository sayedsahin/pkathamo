<?php

namespace App\Systems;

final class MiddlewareKernel
{
    private array $web = [];
    private array $api = [];

    public function web(array $middlewares): void
    {
        $this->web = $middlewares;
    }

    public function api(array $middlewares): void
    {
        $this->api = $middlewares;
    }

    public function run(bool $isApi): void
    {
        $stack = $isApi ? $this->api : $this->web;

        foreach ($stack as $middleware) {
            (new $middleware)->handle();
        }
    }
}
