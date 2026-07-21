# Command-Line Tools

## Rebuild configuration cache

```bash
php bin/cache-config.php
```

This command:

1. loads `.env`
2. loads cacheable `config/*.php` files
3. validates that configuration values can be exported
4. writes `storage/cache/config.php` atomically

Run it after changing `.env` or cached configuration.

## Rebuild route cache

```bash
php bin/cache-routes.php
```

This removes the previous FastRoute cache and generates a new `storage/cache/route.cache`.

Run it after changing routes for a production deployment.

## Clear generated caches manually

Linux/macOS:

```bash
rm -f storage/cache/config.php storage/cache/route.cache
```

PowerShell:

```powershell
Remove-Item storage/cache/config.php, storage/cache/route.cache -ErrorAction SilentlyContinue
```

The next web bootstrap rebuilds configuration cache. FastRoute rebuilds route cache when production routing runs.

## APCu CLI limitation

CLI APCu is normally separate from web-server APCu and often disabled. A CLI cache clear cannot be relied on to clear PHP-FPM APCu. Use Redis or Memcached when CLI and web processes must share cache control.

## Release process

Before creating a release archive:

1. remove `.env`
2. remove generated configuration and route caches
3. retain `.env.example`
4. retain `storage/cache/README.md`
5. run syntax checks and tests
6. regenerate caches only on the target deployment
