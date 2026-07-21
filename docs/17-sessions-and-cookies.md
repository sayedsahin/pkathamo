# Sessions and Cookies

Sessions are available for web requests. API requests skip session bootstrap by default.

## Configuration

```php
return [
    'driver' => env('SESSION_DRIVER', 'native'),
    'lifetime' => env('SESSION_LIFETIME', 7200),
    'secure' => env('SESSION_SECURE', true),
    'samesite' => env('SESSION_SAMESITE', 'Lax'),
];
```

## Session API

Static facade:

```php
use App\Systems\Session\Session;

Session::set('key', $value);
$value = Session::get('key', $default);
Session::forget('key');
Session::regenerate();
Session::flush();
Session::destroy();
Session::close();
```

Helper proxy:

```php
session()->set('key', $value);
$value = session()->get('key');
```

## Native session security

The native driver enables:

- strict mode
- cookie-only sessions
- HttpOnly cookies
- configured SameSite
- Secure cookies only when configured and the request is HTTPS
- configured garbage-collection lifetime

Call `Session::regenerate()` after changing authentication state. `Auth::login()` already does this.

## Session locking

PHP native file sessions can hold a lock for the duration of a request. After all session writes are complete, long-running work may call:

```php
Session::close();
```

Do not attempt to write session data after closing it unless the driver is restarted appropriately.

## Cookies

```php
use App\Systems\Session\Cookie;

Cookie::set('theme', 'dark', 86400);
$value = Cookie::get('theme');
Cookie::forget('theme');
```

Cookies are set as HttpOnly, use `/` as the path, and derive the Secure flag from the request.

## Remember token generation

```php
$token = RememberToken::generate();
```

It returns:

```php
[
    'raw' => 'token sent to the cookie',
    'hash' => 'SHA-256 value stored in the database',
]
```
