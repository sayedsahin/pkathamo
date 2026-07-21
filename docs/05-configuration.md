# Configuration

Configuration files are stored in `config/`. Each cacheable file must return an array containing only arrays, strings, numbers, booleans or `null`.

## Reading configuration

```php
$name = config('app.name');
$debug = config('app.debug', false);
$redis = config('database.redis', []);
```

Read all loaded configuration:

```php
$all = config();
```

## Runtime configuration

Set one value:

```php
config(['app.debug' => true]);
```

Set several values:

```php
config([
    'app.debug' => true,
    'cache.driver' => 'array',
]);
```

Runtime changes are request-local and clear the configuration lookup memo.

## Environment values

```php
'value' => env('KEY', 'default')
```

The helper converts common scalar strings:

- `true`, `(true)` → `true`
- `false`, `(false)` → `false`
- `null`, `(null)` → `null`
- `empty`, `(empty)` → `''`
- numeric strings → integer or float

## Configuration cache

At boot, Pkathamo uses `storage/cache/config.php` when it exists. Otherwise it loads `.env`, reads config files, writes the cache, then loads it.

After changing `.env` or a cached config file:

```bash
php bin/cache-config.php
```

## Files excluded from the configuration cache

- `config/path.php`
- `config/routes.php`

## Adding a configuration file

Create `config/mail.php`:

```php
<?php

declare(strict_types=1);

return [
    'host' => env('MAIL_HOST', '127.0.0.1'),
    'port' => (int) env('MAIL_PORT', 25),
];
```

Read it with:

```php
$host = config('mail.host');
```
