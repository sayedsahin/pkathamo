# Rate Limiting

Rate limiting is included as global web and API middleware.

## Configuration

`config/rate_limit.php` defines:

- driver
- key prefix
- guest web policy
- authenticated web policy
- API policy
- sensitive-route policy
- file-driver cleanup settings

## Included policy behavior

Sensitive routes use method and normalized path:

```text
POST /login
POST /register
POST /api/auth/login
POST /api/auth/register
POST /api/auth/forgot
```

Other API requests use the client IP. Authenticated web requests use the user ID; guest web requests use the client IP.

## Response headers

Allowed and rejected requests can include:

```text
X-RateLimit-Limit
X-RateLimit-Remaining
X-RateLimit-Reset
Retry-After
```

Rejected API request:

```json
{
  "error": "Too many requests",
  "retry_after": 30
}
```

## Direct API

```php
$result = RateLimiter::hit('login:ip:' . request()->ip(), 5, 60);

if (!$result->allowed()) {
    return response()->json([
        'error' => 'Too many attempts',
        'retry_after' => $result->retryAfter(),
    ], 429);
}
```

Clear a key:

```php
RateLimiter::clear('login:ip:' . request()->ip());
```

Reset the resolved driver instance:

```php
RateLimiter::reset();
```

## Result object

```php
$result->allowed();
$result->limit();
$result->attempts();
$result->remaining();
$result->retryAfter();
$result->resetAt();
```

## Drivers

- File: portable, locked local counters
- APCu: low-latency local counters with environment limitations
- Redis: atomic Lua increment and expiry
- Memcached: atomic increment with separate reset timestamp

## Multiple Memcached servers

The Memcached client hashes each rate-limit key to one configured server. Adding or removing servers changes key distribution and may temporarily reset counters. Use Redis when strict distributed rate-limit continuity is essential.

## Failures

The current drivers throw when a rate-limit operation cannot be completed. The central exception handler converts uncaught failures to a 500 response. Choose and monitor a reliable production store.
