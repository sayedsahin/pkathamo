<?php

declare(strict_types=1);

namespace App\Systems;

final class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;
    private array $cookies;
    private array $headers;
    private ?string $rawBody = null;

    private function __construct(
        array $get,
        array $post,
        array $server,
        array $files,
        array $cookies
    ) {
        $this->get     = $get;
        $this->post    = $post;
        $this->server  = $server;
        $this->files   = $files;
        $this->cookies = $cookies;
        $this->headers = $this->parseHeaders($server);
    }

    public static function capture(): self
    {
        return new self($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE);
    }

    /* -----------------------
       Basic Accessors
    ----------------------- */

    public function all(): array
    {
        // POST takes precedence over GET
        return $this->post + $this->get;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->post)) {
            return $this->post[$key];
        }

        return $this->get[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function file(string $key): mixed
    {
        return $this->files[$key] ?? null;
    }

    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }

    /* -----------------------
       Raw & JSON Body
    ----------------------- */

    public function getRawBody(): string
    {
        if ($this->rawBody === null) {
            $this->rawBody = file_get_contents('php://input') ?: '';
        }
        return $this->rawBody;
    }

    public function json(?string $key = null, mixed $default = null): mixed
    {
        $body = $this->getRawBody();
        $data = json_decode($body, true);

        if (!is_array($data)) {
            $data = [];
        }

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? $default;
    }

    /* -----------------------
       Meta
    ----------------------- */

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($method) === $this->method();
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';

        $path = parse_url($uri, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        $path = rawurldecode($path);

        return '/' . trim($path, '/');
    }

    public function fullUrl(): string
    {
        $scheme = $this->isSecure() ? 'https' : 'http';

        return $scheme . '://' . $this->host() . ($this->server['REQUEST_URI'] ?? '/');
    }

    public function host(): string
    {
        if (TrustedProxy::isSecureRequest($this->server)) {
            $forwardedHost =
                $this->server['HTTP_X_FORWARDED_HOST'] ?? '';

            if ($forwardedHost !== '') {
                return trim(explode(',', $forwardedHost)[0]);
            }
        }

        return $this->server['HTTP_HOST'] ?? 'localhost';
    }

    public function ip(): ?string
    {
        // return $this->server['REMOTE_ADDR'] ?? null;
        return TrustedProxy::clientIp($this->server);
    }

    public function isSecure(): bool
    {
        // return isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off';
        return TrustedProxy::isSecureRequest($this->server);
    }

    /* -----------------------
       Headers
    ----------------------- */

    private function parseHeaders(array $server): array
    {
        $headers = [];

        foreach ($server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace('_', '-', substr($key, 5));
                $headers[strtolower($name)] = $value;
            }
        }

        return $headers;
    }

    public function header(string $key, mixed $default = null): mixed
    {
        return $this->headers[strtolower($key)] ?? $default;
    }

    public function bearerToken(): ?string
    {
        $header = $this->header('authorization');

        if ($header && str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }

        return null;
    }
}
