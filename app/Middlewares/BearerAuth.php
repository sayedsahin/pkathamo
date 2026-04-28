<?php

namespace App\Middlewares;

use App\Models\DB;
use App\Supports\Auth;

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
        $db = new DB;
        $hashed = hash('sha256', $token);
        $record = $db->table('api_tokens')
            ->where('token', $hashed)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        if ($record) {
            Auth::once($record->user_id);
            return true;
        }
        return false;
    }
}

