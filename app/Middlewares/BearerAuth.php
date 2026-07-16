<?php

namespace App\Middlewares;

use App\Models\DB;
use App\Supports\Auth;
use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;

class BearerAuth implements MiddlewareInterface
{
    public function handle(): ?Response
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!str_starts_with($header, 'Bearer ')) {
            return response()
                ->header('WWW-Authenticate', 'Bearer')
                ->json(['error' => 'Unauthorized'], 401);
        }

        $token = substr($header, 7);

        if (!$this->validate($token)) {
            return response()
                ->json(['error' => 'Invalid token'], 401);
        }

        return null;
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

