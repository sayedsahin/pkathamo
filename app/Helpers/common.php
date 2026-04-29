<?php

use App\Models\DB;
use App\Supports\RequestContext;
use App\Supports\Role;
use App\Systems\Cache\Cache;
use App\Systems\Session\Session;

if (!function_exists('cache')) {
    function cache(): Cache
    {
        return new Cache;
    }
}

if (!function_exists('db')) {
    function db(): DB
    {
        $db = RequestContext::get('db');

        if ($db) {
            return $db;
        }
        $db = new DB();

        RequestContext::set('db', $db);

        return $db;
    }
}

if (!function_exists('role')) {
    function role(string $role): bool
    {
        return Role::has($role);
    }
}

if (!function_exists('roles')) {
    function roles(array $roles): bool
    {
        return Role::any($roles);
    }
}

if (!function_exists('session')) {
    function session()
    {
        static $proxy = null;

        if ($proxy === null) {
            $proxy = new class {
                public function __call($method, $args)
                {
                    return Session::$method(...$args);
                }
            };
        }

        return $proxy;
    }
}

