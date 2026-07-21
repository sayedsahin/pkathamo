# Installation

## Requirements

- PHP 8.2 or newer
- Composer
- PDO
- PDO MySQL for the bundled MySQL connection
- Mbstring for validation length rules
- A web server whose document root points to `public/`

Optional extensions:

- APCu for APCu cache or rate limiting
- PhpRedis for Redis cache or rate limiting
- Memcached extension for Memcached cache or rate limiting
- GD for `ImageHelper`

## Install dependencies

From the project root:

```bash
composer install
```

After adding or moving application classes:

```bash
composer dump-autoload
```

## Environment file

Copy the example file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Configure at least:

```dotenv
DEBUG_MODE=true
BASE_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=pkathamo
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=native
CACHE_DRIVER=file
RATE_LIMIT_STORE=file
```

## Database schema

The starter archive includes `database/backup.sql`. Import it into the configured database when using the bundled authentication examples.

## Writable directories

The web-server user must be able to write to:

```text
storage/cache/
storage/cache/file-cache/
storage/cache/rate-limit/
```

The file drivers create their own subdirectories, but the parent storage path still needs suitable permissions.

## Development start

For PHP's development server:

```bash
php -S 127.0.0.1:8000 -t public
```

Set:

```dotenv
BASE_URL=http://127.0.0.1:8000
DEBUG_MODE=true
```

Do not use the built-in server as the production server.

## Refresh cached configuration

After changing `.env` or files in `config/`:

```bash
php bin/cache-config.php
```

See [Command-line tools](24-command-line-tools.md).
