<?php

namespace App\Middlewares;


class Csrf implements MiddlewareInterface
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        session_start();

        $token = $_POST['_csrf'] ?? '';

        if (!hash_equals($_SESSION['_csrf'] ?? '', $token)) {
            http_response_code(419);
            exit('CSRF token mismatch');
        }
    }
}

