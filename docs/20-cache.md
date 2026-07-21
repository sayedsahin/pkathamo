# Cache

Use the cache helper:

```php
cache()->put('key', $value, 300);
$value = cache()->get('key');
```

## API

```php
cache()->get($key, $default);
cache()->put($key, $value, $ttlSeconds);
cache()->has($key);
cache()->forget($key);
cache()->flush();
```

Remember a value:

```php
$user = cache()->remember('user:' . $id, 300, function () use ($id) {
    return db()->table('users')->find($id);
});
```

`remember()` is not an exactly-once lock. Concurrent cache misses may execute the callback more than once.

## Lazy driver initialization

`bootstrap/cache.php` registers a resolver. The selected driver is not instantiated until the first cache operation.

## Drivers

### Array

```dotenv
CACHE_DRIVER=array
```

- Current PHP request only
- Useful for tests
- No cross-request persistence

### File

```dotenv
CACHE_DRIVER=file
```

- Portable fallback
- Shared by local PHP workers
- Uses shared/exclusive file locking
- Requires writable storage

### APCu

```dotenv
CACHE_DRIVER=apcu
```

- Very low latency in supported web environments
- Not shared with CLI cache
- Windows process-based workers may have separate caches
- Not suitable for multiple servers or containers

### Redis

```dotenv
CACHE_DRIVER=redis
```

- Shared network cache
- Uses `database.redis.cache_db`
- Suitable for multiple web servers and CLI workers
- Requires PhpRedis and a Redis service

### Memcached

```dotenv
CACHE_DRIVER=memcached
```

- Shared network cache
- Uses `database.memcached`
- Supports several configured servers
- Uses namespace versioning for application-specific `flush()`
- Requires the Memcached PHP extension and server

## Prefixes

Cache keys use the configured cache prefix. Use a unique prefix per application sharing the same cache service.

```php
'prefix' => 'my-app:cache:'
```

## TTL

`0` means no explicit expiration. Positive values are seconds. Memcached converts durations over 30 days into absolute expiration timestamps.

## Driver selection

| Environment | Recommended driver |
|---|---|
| Tests | Array |
| Portable single server | File |
| Supported Linux single server | APCu |
| Multiple servers or CLI sharing | Redis or Memcached |
