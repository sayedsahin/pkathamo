# Database

Pkathamo uses PDO. The `Database` service is configured as a container singleton so one PDO connection is reused during a request.

## Configuration

```php
'connections' => [
    'mysql' => [
        'driver' => env('DB_CONNECTION', 'mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_NAME', ''),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'options' => [
            'persistent' => env('DB_PERSISTENT', false),
        ],
    ],
],
```

PDO uses:

- exception error mode
- associative default fetch mode
- native prepared statements
- optional persistent connection

## Query access

```php
$users = db()->table('users')->get();
```

`db()` reuses the singleton `Database` but returns a fresh Query Builder for each call.

## Direct PDO access

```php
use App\Systems\Database;

final class ImportService
{
    public function __construct(private Database $database)
    {
    }

    public function run(): void
    {
        $pdo = $this->database->pdo;
    }
}
```

## Transactions

The current core exposes PDO, so transactions can be managed directly:

```php
$database = $container->make(\App\Systems\Database::class);
$pdo = $database->pdo;

$pdo->beginTransaction();

try {
    db()->table('users')->insert($user);
    db()->table('user_roles')->insert($role);
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    throw $e;
}
```

Because all builders use the same request-level PDO connection, these queries participate in the transaction.

## Schema constraints

Application correctness must also be enforced in the database. Use unique indexes for unique email, username and user-role pairs, and foreign keys where appropriate.
