# Request

Use the shared request object:

```php
$request = request();
```

## GET and POST input

```php
$all = request()->all();
$email = request()->input('email');
$page = request()->query('page', 1);
$name = request()->post('name');
```

`all()` merges POST and GET values, with POST taking precedence. It does not automatically include JSON data or raw request bodies.

## JSON

```php
$data = request()->json();
$email = request()->json('email');
$email = request()->json('email', 'default@example.com');
```

The raw body is read once and JSON is decoded once per request.

## Raw body

```php
$raw = request()->getRawBody();
```

## Files and cookies

```php
$file = request()->file('avatar');
$theme = request()->cookie('theme', 'light');
```

## Method

```php
$method = request()->method();

if (request()->isMethod('POST')) {
    // ...
}
```

## URL and path

```php
$path = request()->path();
$url = request()->fullUrl();
$host = request()->host();
```

`path()` normalizes the root to `/` and removes trailing slashes from other paths.

## Headers

```php
$accept = request()->header('accept');
$csrf = request()->header('x-csrf-token');
```

Headers are looked up lazily from the captured server array.

## Bearer token

```php
$token = request()->bearerToken();
```

It supports both `HTTP_AUTHORIZATION` and `REDIRECT_HTTP_AUTHORIZATION`.

## Client IP and HTTPS

```php
$ip = request()->ip();
$secure = request()->isSecure();
```

Forwarded values are trusted only when `REMOTE_ADDR` is listed in `app.trusted_proxies`.
