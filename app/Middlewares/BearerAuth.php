<?php

namespace App\Middlewares;


class BearerAuth implements MiddlewareInterface
{
    public function handle(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!str_starts_with($header, 'Bearer ')) {
            http_response_code(401);
            header('WWW-Authenticate: Bearer');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $token = substr($header, 7);

        if (!$this->validate($token)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            exit;
        }
    }

    private function validate(string $token): bool
    {
        // token validation logic
        return true;
    }
}

