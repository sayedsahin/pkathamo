<?php

use App\Systems\Session\Session;

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

if (!function_exists('view')) {
    function component(string $name, array $data = []): void
    {
        extract($data);

        require VIEW_PATH . "/components/{$name}.php";
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
    $token = Session::get('_csrf');

    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('_csrf', $token);
    }

    return $token;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    // This function is now handled by the Csrf middleware, so you don't need to call it manually in your controllers.

    // $session_token = Session::get('_csrf');

    // $token = $_POST['_csrf'] ?? '';

    // if (!hash_equals($session_token ?? '', $token)) {
    //     http_response_code(419);
    //     exit('CSRF token mismatch');
    // }
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