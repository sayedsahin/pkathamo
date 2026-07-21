# Performance

Pkathamo's performance strategy is architectural rather than based on unsafe micro-optimizations.

## Low-overhead choices

- Cached FastRoute dispatcher
- Cached and memoized configuration
- Direct middleware loop
- Reflection metadata cache in the container
- One request object per request
- Lazy request-header lookup
- One-time JSON body decoding
- One PDO connection per request
- Fresh Query Builder per `db()` call
- Lazy cache driver initialization
- Direct `PDO::FETCH_COLUMN` for `pluck()` and `value()`
- Request-scoped authentication and role caches
- Central exception processing only on errors

## Main real-world costs

In most applications, latency is dominated by:

- database connection and queries
- Redis or Memcached network round trips
- session file locks
- file cache and file rate-limit I/O
- rendering and application logic
- external APIs

Router, response and simple middleware overhead is usually much smaller.

## Query guidance

- Select only needed columns.
- Add indexes for WHERE, JOIN and token lookup columns.
- Use `exists()` instead of retrieving rows only to test existence.
- Use `pluck()` or `value()` for scalar data.
- Prevent N+1 query patterns in application code.
- Use transactions for related writes.

## Cache guidance

- Cache expensive, repeatable work rather than cheap values.
- Include identity and scope in keys: `user:{$id}` rather than `user`.
- Use finite TTLs for data that changes.
- Remember that `remember()` can compute twice under concurrent misses.
- Avoid network caching when a database query is already cheaper than the network round trip.

## Session guidance

Close native sessions before long-running work when no more writes are needed:

```php
Session::close();
```

## Driver count

Having several driver classes does not slow requests. Composer loads only referenced classes, the cache resolver instantiates only the selected cache driver, and the rate limiter resolves only its configured driver. The main cost is maintenance and testing, not runtime.

## Benchmarking

Benchmark real PHP-FPM endpoints, not only CLI method calls. Include:

- empty HTML route
- JSON route
- session route
- database SELECT
- authenticated API route
- cache hit and miss
- rate-limited route

Measure requests per second, p50/p95 latency, memory and error rate under realistic concurrency.
