# Query Builder

The Query Builder is a mutable PDO query object. Terminal methods execute the query and reset query-specific state. A model's table is preserved after reset.

## Start a query

```php
$users = db()->table('users')->get();
```

Model query:

```php
$users = User::query()->get();
```

## Select columns

```php
$users = db()->table('users')
    ->select('id', 'name', 'email')
    ->get();
```

## WHERE conditions

```php
$user = db()->table('users')
    ->where('email', $email)
    ->first();
```

Operator form:

```php
$users = db()->table('users')
    ->where('created_at', '>=', $date)
    ->get();
```

OR condition:

```php
$user = db()->table('users')
    ->where('username', $login)
    ->orWhere('email', $login)
    ->first();
```

Null conditions:

```php
->whereNull('deleted_at')
->whereNotNull('email_verified_at')
```

LIKE:

```php
->like('name', '%John%')
```

Several equality conditions:

```php
->whereConditions([
    'status' => 'active',
    'role_id' => 2,
])
```

## Raw WHERE expressions

```php
$users = db()->table('users')
    ->whereRaw('YEAR(created_at) = ?', [2026])
    ->get();
```

The number of `?` placeholders must equal the number of bindings. The SQL expression itself must be developer-controlled.

## Joins

```php
$users = db()->table('users')
    ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
    ->select('users.id', 'profiles.bio')
    ->get();
```

Available methods:

```php
->join($table, $first, $operator, $second, $type)
->innerJoin($table, $first, $operator, $second)
->leftJoin($table, $first, $operator, $second)
```

## Ordering and limits

```php
$users = db()->table('users')
    ->order('created_at DESC')
    ->limit(20)
    ->get();
```

Offset:

```php
->limit(20, 40)
```

Order expressions must remain developer-controlled.

## Read methods

```php
->get();
->first();
->find(5);
->find(10, 'user_id');
->exists();
->count();
->count('email');
->pluck('email');
->value('email');
```

`first()` and `value()` add `LIMIT 1` for builder-generated SQL. Complete raw SQL remains developer-controlled.

## Insert

```php
$ok = db()->table('users')->insert([
    'name' => 'Rahim',
    'email' => 'rahim@example.com',
]);
```

Return the inserted ID:

```php
$id = db()->table('users')->insert($data, true);
```

## Update

```php
$ok = db()->table('users')
    ->where('id', 5)
    ->update(['status' => 'active']);
```

Update without a WHERE condition is forbidden.

## Update or insert

```php
$result = db()->table('settings')->updateOrInsert(
    ['key' => 'theme'],
    ['value' => 'dark']
);
```

## Delete

```php
$ok = db()->table('users')
    ->where('id', 5)
    ->delete();
```

Delete without a WHERE condition is forbidden.

## Raw SELECT

```php
$users = db()->raw(
    'SELECT * FROM users WHERE status = ?',
    ['active']
)->get();
```

## Raw INSERT, UPDATE or DELETE

```php
$ok = db()->raw(
    'UPDATE users SET status = ? WHERE id = ?',
    ['active', 5]
)->execute();
```

Return a raw insert ID:

```php
$id = db()->raw(
    'INSERT INTO users (name, email) VALUES (?, ?)',
    ['Rahim', 'rahim@example.com']
)->execute(true);
```

## Compile SQL

```php
$sql = db()->table('users')
    ->where('status', 'active')
    ->toSql();
```

## Security contract

Prepared statements protect bound values. These arguments are SQL structure and must be developer-controlled:

- table names
- selected columns
- column names
- operators
- order expressions
- join expressions
- complete raw SQL

Safe:

```php
->where('email', request()->input('email'))
```

Unsafe application code:

```php
->order(request()->query('sort'))
```

Map user choices through an application allowlist before using them as identifiers or SQL expressions.
