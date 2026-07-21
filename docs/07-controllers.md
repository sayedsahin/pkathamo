# Controllers

Controllers live in `app/Controllers` and commonly extend the base controller.

```php
namespace App\Controllers;

use App\Systems\Response;

final class UserController extends Controller
{
    public function index(): Response
    {
        $users = db()->table('users')->get();

        return response()->json(['users' => $users]);
    }
}
```

## Rendering a view

```php
public function index(): void
{
    $users = db()->table('users')->get();

    view('users.index', [
        'title' => 'Users',
        'users' => $users,
    ]);
}
```

## Controller-level middleware

Execute one middleware immediately:

```php
public function dashboard(): Response
{
    $this->middleware(Authenticated::class);

    return response()->html('Dashboard');
}
```

Several middleware:

```php
$this->middlewares([
    Authenticated::class,
    [RoleMiddleware::class, ['admin']],
]);
```

A blocking controller middleware sends its response and exits. Prefer route middleware when the same rule naturally belongs to the route definition.

## Dependency injection

Controllers are resolved through the service container, so class-typed constructor dependencies are supported.

```php
final class ReportController extends Controller
{
    public function __construct(private ReportService $reports)
    {
    }
}
```

The dependency must be instantiable or configured in `config/container.php`.
