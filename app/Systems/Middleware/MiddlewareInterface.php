<?php
namespace App\Systems\Middleware;

use App\Systems\Response;

interface MiddlewareInterface
{
    public function handle(): ?Response;
}
