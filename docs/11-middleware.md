# Middleware

Middleware implements:

```php
namespace App\Systems\Middleware;

use App\Systems\Response;

interface MiddlewareInterface
{
    public function handle(): ?Response;
}
```

Return `null` to continue or a `Response` to stop the request.

## Create middleware

```php
namespace App\Middlewares;

use App\Systems\Middleware\MiddlewareInterface;
use App\Systems\Response;

final class VerifiedEmail implements MiddlewareInterface
{
    public function handle(): ?Response
    {
        $user = \App\Supports\Auth::user();

        if (!$user || !$user->email_verified) {
            return response()->html('Email verification required', 403);
        }

        return null;
    }
}
```

## Global middleware

Configure stacks in `config/middleware.php`:

```php
return [
    'web' => [
        WebHeaders::class,
        SessionStart::class,
        RateLimit::class,
        RememberMe::class,
        Csrf::class,
    ],
    'api' => [
        ApiHeaders::class,
        RateLimit::class,
    ],
];
```

## Route middleware

```php
$route->get('/dashboard', [DashboardController::class, 'index', [
    Authenticated::class,
]]);
```

## Middleware arguments

Parameterized middleware constructors receive one array:

```php
[RoleMiddleware::class, ['admin', 'editor']]
```

```php
public function __construct(private array $roles)
{
}
```

## Included middleware

| Middleware | Purpose |
|---|---|
| `WebHeaders` | Web security headers and CSP |
| `ApiHeaders` | JSON, no-store and nosniff headers |
| `SessionStart` | Starts the configured session |
| `RateLimit` | Applies request rate-limit policy |
| `RememberMe` | Restores and rotates remember tokens |
| `Csrf` | Protects state-changing web requests |
| `Authenticated` | Requires an authenticated user |
| `Guest` | Redirects authenticated users away from guest pages |
| `BearerAuth` | Validates API bearer tokens |
| `RoleMiddleware` | Requires any configured role |

## Execution behavior

- Global middleware response: sent, then request exits.
- Route middleware response: sent, then routing returns.
- Controller middleware response: sent, then request exits.
- `Response::send()` itself does not call `exit`.
