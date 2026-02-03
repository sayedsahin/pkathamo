<?php
namespace App\Middlewares;

class WebHeaders implements MiddlewareInterface
{
    public function handle(): void
    {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');

        header(
            "Content-Security-Policy: " .
            "default-src 'self'; " .
            "script-src 'self'; " .
            "style-src 'self' 'unsafe-inline'; " .
            "img-src 'self' data:;"
        );
    }
}
