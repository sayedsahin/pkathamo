# Routing

Routes are defined in `config/routes.php` using FastRoute.

## Basic routes

```php
$route->get('/', [HomeController::class, 'index']);
$route->post('/login', [AuthController::class, 'loginProcess']);
```

The handler array contains:

```text
[ControllerClass::class, 'method', optional middleware array]
```

## Route parameters

```php
$route->get('/users/{id:\\d+}', [UserController::class, 'show']);
```

Controller:

```php
public function show(string $id): Response
{
    $user = db()->table('users')->find((int) $id);

    return response()->json(['user' => $user]);
}
```

Matched variables are passed to the controller in FastRoute's returned order.

## Route middleware

```php
$route->get('/account', [AccountController::class, 'index', [
    Authenticated::class,
]]);
```

Parameterized middleware receives one array argument:

```php
$route->get('/admin', [AdminController::class, 'index', [
    Authenticated::class,
    [RoleMiddleware::class, ['admin', 'editor']],
]]);
```

## API routes

Routes beginning with `/api` use the API middleware stack and JSON-style 404/405 responses.

```php
$route->get('/api/profile', [ApiAuthController::class, 'profile', [
    BearerAuth::class,
]]);
```

## 404 and 405

- Web requests receive plain 404/405 responses.
- API requests receive JSON.
- 405 responses include the `Allow` header.

## Route cache

When debug mode is disabled, FastRoute uses:

```text
storage/cache/route.cache
```

Rebuild before deployment:

```bash
php bin/cache-routes.php
```

Do not package a route cache generated on another release or machine.
