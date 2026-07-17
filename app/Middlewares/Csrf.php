<?php

namespace App\Middlewares;

use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;
use App\Systems\Session\Session;

class Csrf implements MiddlewareInterface
{
    public function handle(): ?Response
    {
        $method = request()->method();
        if (!in_array($method, ['POST', 'PUT', 'PATCH'], true)){
            return null;
        }

        $session_token = Session::get('_csrf');

        $token = request()->post('_csrf') ?? '';

        if (!hash_equals($session_token ?? '', $token)) {
            return response()->html('CSRF token mismatch', 419);
        }

        return null;
    }
}

