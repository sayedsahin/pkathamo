<?php

namespace App\Middlewares;

use App\Supports\Auth;
use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;

class Authenticated implements MiddlewareInterface
{

    public function handle(): ?Response
    {
        if (!Auth::check()) {
            return response()->html('Unauthorized', 401);
        }

        return null;
    }
}