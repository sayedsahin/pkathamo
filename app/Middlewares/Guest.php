<?php

namespace App\Middlewares;

use App\Supports\Auth;

class Guest implements MiddlewareInterface
{

    public function handle(): void
    {
        if (Auth::check()) {
            redirect()->to('/');
            exit;
        }
    }
}