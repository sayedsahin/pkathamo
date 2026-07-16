<?php

namespace App\Middlewares;

use App\Supports\Auth;
use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;

class Guest implements MiddlewareInterface
{

    public function handle(): ?Response
    {
        if (Auth::check()) {
            // dd('You are already logged in. Redirecting to home page...');
            return response()->redirect('/');
        }

        return null;
    }
}
