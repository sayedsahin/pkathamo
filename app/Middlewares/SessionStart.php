<?php
namespace App\Middlewares;

use App\Systems\Session\Session;

final class SessionStart implements MiddlewareInterface
{
    public function handle(): void
    {
        Session::start();
    }
}
