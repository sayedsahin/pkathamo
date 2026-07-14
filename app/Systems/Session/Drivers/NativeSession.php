<?php
namespace App\Systems\Session\Drivers;

use App\Systems\Session\SessionInterface;
use App\Systems\TrustedProxy;

final class NativeSession implements SessionInterface
{
    private bool $started = false;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function start(): void
    {
        if ($this->started) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start([
                'use_strict_mode'   => 1,
                'use_only_cookies'  => 1,
                'cookie_httponly'   => true,
                'cookie_secure'    => $this->config['secure'] && TrustedProxy::isSecureRequest($_SERVER),
                'cookie_samesite'  => $this->config['samesite'],
                // 'gc_maxlifetime'   => $this->config['lifetime'],
            ]);
        }

        $this->started = true;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flush(): void
    {
        $_SESSION = [];
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                [
                    'expires'  => time() - 3600,
                    'path'     => $params['path'],
                    'domain'   => $params['domain'],
                    'secure'   => $params['secure'],
                    'httponly' => $params['httponly'],
                    'samesite' => $params['samesite'] ?? 'Lax'
                ]
            );
        }

        session_destroy();
    }
}
