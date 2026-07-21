# Introduction

Pkathamo provides a compact framework core without a long-running async runtime or a large service-provider graph.

## Design goals

- Standard PHP-FPM and Apache compatibility
- Small request bootstrap
- Direct middleware execution
- PDO-based database access
- Built-in request, response, validation, session, authentication, cache and rate limiting
- Driver-based cache and rate-limit storage
- Minimal magic and predictable control flow

## Main components

| Component | Purpose |
|---|---|
| FastRoute | HTTP route matching and route cache |
| Request | GET, POST, files, cookies, JSON, headers and request metadata |
| Response | HTML, JSON, headers, status and redirects |
| MiddlewareKernel | Global and route middleware execution |
| QueryBuilder | Fluent PDO query construction and raw SQL execution |
| Validator | Fluent validation with filtered validated data |
| Session | Native or null session driver facade |
| Auth | Session and bearer authentication state |
| Cache | Array, file, APCu, Redis or Memcached storage |
| RateLimiter | File, APCu, Redis or Memcached counters |
| ExceptionHandler | Central HTML, JSON and CLI error handling |
| Container | Constructor dependency resolution and singletons |

## Web and API requests

A request is considered an API request when its normalized path is exactly `/api` or begins with `/api/`.

```php
function is_api_request(): bool
{
    $path = request()->path();

    return $path === '/api' || str_starts_with($path, '/api/');
}
```

This keeps behavior deterministic and independent of the `Accept` header.
