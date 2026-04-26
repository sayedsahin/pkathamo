<?php

namespace App\Middlewares;

use App\Supports\Auth;
use App\Supports\Role;

class RoleMiddleware implements MiddlewareInterface
{
    private array $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function handle(): void
    {
        if (!Auth::check()) {
            http_response_code(401);
            exit('Unauthorized');
        }

        if (!Role::any($this->roles)) {
            http_response_code(403);
            exit('Forbidden');
        }
    }
}