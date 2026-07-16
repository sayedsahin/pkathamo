<?php
namespace App\Middlewares;

use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;
use App\Systems\Session\Session;

final class SessionStart implements MiddlewareInterface
{
    public function handle(): ?Response
    {
        Session::start();
        return null;
    }
}
