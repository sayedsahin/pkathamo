<?php

namespace App\Middlewares;

use App\Systems\Session\Session;
use App\Systems\Session\RememberToken;
use App\Systems\Session\Cookie;
use App\Supports\Auth;

final class RememberMe implements MiddlewareInterface
{
    public function handle(): void
    {
        if (Session::get('auth_user_id')) {
            return;
        }

        $raw = Cookie::get('remember_token');
        if (!$raw) {
            return;
        }

        $hash = hash('sha256', $raw);
        $row = db()->table('remember_tokens')
            ->where('token_hash', $hash)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        if (!$row) {
            Cookie::forget('remember_token');
            return;
        }

        if ($row->user_agent !== $_SERVER['HTTP_USER_AGENT']) {
            Cookie::forget('remember_token');
            return;
        }

        Session::regenerate();
        Session::set('auth_user_id', $row->user_id);
        Auth::setViaRemember(true);

        // Rotate token
        $new = RememberToken::generate();

        db()->table('remember_tokens')->where('id', $row->id)->update([
            'token_hash' => $new['hash'],
        ]);

        Cookie::set('remember_token', $new['raw'], 86400 * 30);
    }
}
