# Models

Models are lightweight Query Builder subclasses.

```php
namespace App\Models;

use App\Systems\QueryBuilder;

final class User extends QueryBuilder
{
    protected string $table = 'users';
}
```

## Recommended query syntax

```php
$users = User::query()
    ->select('id', 'name', 'email')
    ->get();
```

`query()` creates a fresh model instance:

```php
public static function query(): static
{
    return new static();
}
```

This has effectively the same object-creation cost as `new User()` and avoids pending query state being shared between separate query chains.

## Instance syntax

```php
$model = new User();
$users = $model->select('id', 'email')->get();
```

The model table is preserved when terminal methods reset the query state, so sequential use after a completed query is supported. Do not branch multiple unfinished queries from the same mutable instance.

## Add model methods

```php
final class User extends QueryBuilder
{
    protected string $table = 'users';

    public function active(): self
    {
        return $this->where('status', 'active');
    }
}
```

Usage:

```php
$users = User::query()->active()->get();
```

Models do not provide an ORM entity lifecycle, dirty tracking or automatic relationships. Returned rows are PDO objects.
