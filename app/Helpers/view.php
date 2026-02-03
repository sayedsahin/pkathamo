<?php

if (!function_exists('view')) {
    function view(string $view, array $data = []): void
    {
        $path = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($path)) {
            throw new RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);
        require $path;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('raw')) {
    function raw(mixed $value): string
    {
        return (string) $value;
    }
}

if (!function_exists('view_path')) {
    function view_path(string $view): string
    {
        return APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
    }
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $token = $_POST['_csrf'] ?? '';

    if (!hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('CSRF token mismatch');
    }
}

// function send_security_headers(): void
// {
//     header('X-Frame-Options: SAMEORIGIN');
//     header('X-Content-Type-Options: nosniff');
//     header('X-XSS-Protection: 0'); // modern browsers
//     header('Referrer-Policy: strict-origin-when-cross-origin');
//     header('Permissions-Policy: geolocation=(), microphone=()');

//     // CSP (tight but safe)
//     header(
//         "Content-Security-Policy: default-src 'self'; " .
//         "style-src 'self' 'unsafe-inline'; " .
//         "script-src 'self'; " .
//         "img-src 'self' data:;"
//     );
// }