# Security

## Production debug setting

```dotenv
DEBUG_MODE=false
```

Debug mode exposes exception details and disables the production route cache.

## Document root

Expose only `public/`. Never serve `app/`, `config/`, `storage/`, `.env` or database backups directly.

## Prepared values

Query Builder values are bound through PDO:

```php
->where('email', request()->input('email'))
```

Table names, columns, operators, joins, order expressions and raw SQL are not bindable and must be developer-controlled.

## Output escaping

```php
<?= e($value) ?>
```

Use `raw()` only for trusted HTML.

## CSRF protection

The global web stack protects POST, PUT, PATCH and DELETE requests. Tokens may come from:

- `_csrf` POST field
- `X-CSRF-Token` header
- `_csrf` JSON property

Use `csrf_field()` in HTML forms.

## Sessions

Native sessions use strict mode, cookie-only transport, HttpOnly and SameSite. Set `SESSION_SECURE=true` behind HTTPS and configure trusted proxies correctly when TLS terminates upstream.

## Passwords

Use:

```php
password_hash($password, PASSWORD_DEFAULT);
password_verify($password, $storedHash);
```

Apply a minimum password policy during registration and reset flows.

## Authentication tokens

Store SHA-256 hashes for bearer, remember, verification and reset tokens. Send the raw token only to the intended client.

## Redirects

Prefer internal paths with `redirect('/path')`. Use `away()` only for explicit trusted external destinations. Never pass an unvalidated `next` query parameter directly to a redirect.

## Trusted proxies

Only exact IP addresses currently match `app.trusted_proxies`. CIDR strings are not interpreted by the current implementation.

## Cache and rate-limit services

Do not expose Redis or Memcached to the public internet. Bind them to trusted networks, configure authentication where available and use application-specific prefixes/databases.

## Release packages

Do not distribute:

```text
.env
storage/cache/config.php
storage/cache/route.cache
runtime cache files
```

Generated configuration cache may contain database credentials and machine-specific paths.

## Database constraints

Use unique indexes and foreign keys as the final integrity layer. Application-level existence checks alone are subject to concurrent races.
