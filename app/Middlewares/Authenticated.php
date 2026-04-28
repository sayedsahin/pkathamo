<?php

namespace App\Middlewares;

use App\Supports\Auth;

class Authenticated implements MiddlewareInterface
{

    public function handle(): void
    {
        if (!Auth::check()) {
            http_response_code(401);
            exit('Unauthorized');
        }
    }
}