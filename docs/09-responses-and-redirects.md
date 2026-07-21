# Responses and Redirects

## Create a response

```php
return response('Hello', 200);
```

## HTML

```php
return response()->html('<h1>Hello</h1>');
```

## JSON

```php
return response()->json([
    'status' => 'ok',
], 200);
```

JSON encoding uses `JSON_THROW_ON_ERROR`.

## Status and headers

```php
return response()
    ->status(201)
    ->header('X-App-Version', '1.0')
    ->html('Created', 201);
```

Several headers:

```php
return response()->json($data)->headers([
    'Cache-Control' => 'no-store',
    'X-Request-ID' => $requestId,
]);
```

## Redirects

```php
return response()->redirect('/login');
```

With flash data:

```php
return response()
    ->redirect('/login')
    ->with(['success' => 'Registration successful']);
```

Back to a same-domain referrer:

```php
return response()
    ->redirect()
    ->with(['error' => 'Invalid input'])
    ->back('/');
```

Explicit external redirect:

```php
return response()->redirect()->away('https://example.com');
```

## Sending

Normally the router sends returned `Response` objects. Middleware and controller-level middleware may send a blocking response directly.
