<?php

namespace App\Middlewares;

use App\Systems\Session\Session;

class Csrf implements MiddlewareInterface
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $session_token = Session::get('_csrf');

        $token = $_POST['_csrf'] ?? '';

        if (!hash_equals($session_token ?? '', $token)) {
            http_response_code(419);
            exit('CSRF token mismatch');
        }
    }
}

