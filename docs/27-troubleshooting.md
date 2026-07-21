# Troubleshooting

## `.env` changes have no effect

Configuration is cached. Rebuild it:

```bash
php bin/cache-config.php
```

Or remove `storage/cache/config.php` and let the next bootstrap rebuild it.

## New or changed routes do not appear

```bash
php bin/cache-routes.php
```

In development, enable debug mode to disable route cache.

## APCu callback runs several times

Possible causes:

- Windows or process-isolated PHP workers
- separate FPM pools
- separate containers or servers
- TTL expiration
- eviction
- simultaneous cache misses

Use Redis or Memcached when all workers and CLI processes must share cache consistently.

## APCu is unavailable

Choose another driver:

```dotenv
CACHE_DRIVER=file
RATE_LIMIT_STORE=file
```

Or install and enable APCu in the target web SAPI.

## Redis or Memcached request returns 500

Check:

- extension installed
- service running
- host and port
- authentication
- selected database
- network/firewall access
- central PHP error log

## Session cookie is not created

Check:

- route is a web route, not `/api/*`
- `SESSION_DRIVER=native`
- `SESSION_SECURE` matches HTTPS availability
- trusted proxy configuration when HTTPS terminates upstream
- headers were not already sent

## CSRF mismatch

Ensure the request includes one of:

```php
<?= csrf_field() ?>
```

```http
X-CSRF-Token: TOKEN
```

```json
{"_csrf":"TOKEN"}
```

The session must be active and the client must retain the session cookie.

## Bearer authentication fails

Check:

- `Authorization: Bearer ...` is forwarded by the web server
- FastCGI forwarding rules
- token stored as SHA-256 hash
- token expiration
- route includes `BearerAuth`

## Invalid parameter number

For `whereRaw()`, the number of `?` placeholders must exactly match bindings.

For complete raw SQL, do not mix positional and named parameters in one statement.

## UPDATE or DELETE is forbidden

Pkathamo requires a WHERE condition for builder-generated update and delete operations.

```php
->where('id', $id)->update($data)
->where('id', $id)->delete()
```

For intentionally broad data maintenance, use explicit developer-controlled raw SQL and `execute()`.

## View not found

Dot notation must match a PHP file under `app/Views`:

```php
view('auth.login');
```

```text
app/Views/auth/login.php
```

## Class not found

Run:

```bash
composer dump-autoload
```

Confirm namespace and file path follow the `App\\` PSR-4 mapping.
