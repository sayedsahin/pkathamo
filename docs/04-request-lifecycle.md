# Request Lifecycle

The main entry point is `public/index.php`.

## Bootstrap order

1. Define application paths.
2. Load Composer autoloading.
3. Load cached configuration or build it from `.env` and `config/*.php`.
4. Detect web or API mode from the normalized path.
5. Register the central exception handler.
6. Configure sessions for web requests.
7. Register the lazy cache resolver.
8. Register the authentication user resolver.
9. Build the service container.
10. Run global web or API middleware.
11. Dispatch the route.
12. Run route middleware.
13. Resolve and call the controller.
14. Send a returned `Response`.

## Request object

The `request()` helper lazily captures the PHP superglobals once per request.

```php
$request = request();
```

## Global middleware

Global middleware is configured in `config/middleware.php`. A middleware response is sent immediately and the request exits.

## Route middleware

Route middleware runs after a route is found and before the controller. A returned response is sent and routing returns.

## Controller response

```php
$result = $controller->$action(...array_values($vars));

if ($result instanceof \App\Systems\Response) {
    $result->send();
}
```

A controller that renders a view may return `void`, because `view()` includes the PHP template directly.

## Web and API Request Detection

Pkathamo identifies API requests by their normalized URL path.

A request is considered an API request when its path is exactly `/api` or begins with `/api/`.

```php
function is_api_request(): bool
{
    $path = request()->path();

    return $path === '/api' || str_starts_with($path, '/api/');
}
```
