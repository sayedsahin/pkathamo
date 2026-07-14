<?php

declare(strict_types=1);

use App\Systems\Config\Config;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return value($default);
        }

        if (!is_string($value)) {
            return $value;
        }

        $trimmed = trim($value);
        $lower = strtolower($trimmed);

        return match ($lower) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            'empty', '(empty)' => '',
            default            => cast_env_scalar($trimmed),
        };
    }
}

if (!function_exists('config')) {
    function config(string|array|null $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return Config::all();
        }

        if (is_array($key)) {
            Config::setMany($key);
            return null;
        }

        return Config::get($key, $default);
    }
}

if (!function_exists('value')) {
    function value(mixed $value): mixed
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('cast_env_scalar')) {
    function cast_env_scalar(string $value): mixed
    {
        if ($value === '') {
            return '';
        }

        $first = $value[0] ?? '';
        $last = $value[strlen($value) - 1] ?? '';

        if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
            return substr($value, 1, -1);
        }

        if (preg_match('/^-?\d+$/', $value)) {
            return (int) $value;
        }

        if (preg_match('/^-?\d+\.\d+$/', $value)) {
            return (float) $value;
        }

        return $value;
    }
}
