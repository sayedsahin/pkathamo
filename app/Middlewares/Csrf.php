<?php

namespace App\Middlewares;

use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;
use App\Systems\Session\Session;

class Csrf implements MiddlewareInterface
{
    public function handle(): ?Response
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        $session_token = Session::get('_csrf');

        $token = $_POST['_csrf'] ?? '';

        if (!hash_equals($session_token ?? '', $token)) {
            return response()->html('CSRF token mismatch', 419);
        }

        return null;
    }
}

