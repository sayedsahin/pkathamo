# Deployment

## Production checklist

- Set `DEBUG_MODE=false`.
- Point the document root to `public/`.
- Configure HTTPS.
- Set the correct `BASE_URL`.
- Configure database credentials.
- Choose available cache and rate-limit drivers.
- Make required storage directories writable.
- Import or migrate the database schema.
- Build Composer's optimized autoloader.
- Rebuild configuration and route caches on the target server.
- Configure PHP error logging.
- Ensure `.env` and source directories are not public.

## Composer

```bash
composer install --no-dev --optimize-autoloader
```

## Cache commands

```bash
php bin/cache-config.php
php bin/cache-routes.php
```

## Apache

Set the virtual host document root to the project's `public` directory. The archive includes a `public/.htaccess` rewrite file.

## Nginx example

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/pkathamo/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\. {
        deny all;
    }
}
```

Adjust the PHP-FPM socket to the installed version.

## Reverse proxy

When an upstream proxy terminates HTTPS, add the proxy's exact IP to `TRUSTED_PROXIES`. Forwarded protocol, host and client IP headers are ignored for untrusted remote addresses.

## Driver guidance

| Deployment | Cache | Rate limiting |
|---|---|---|
| Basic single server | File | File |
| Supported Linux single server | APCu | APCu or File |
| Multiple application servers | Redis or Memcached | Redis preferred |
| Containers with shared services | Redis or Memcached | Redis preferred |

## Permissions

Grant write access only where needed. Avoid broad `0777` permissions. The PHP worker needs access to runtime cache paths, not ownership of the entire application source.

## Generated files

Generate configuration and routes on the target environment. Never deploy caches from a developer machine because configuration cache may contain credentials and absolute paths.

## Health verification

After deployment, verify:

- a web route returns 200
- an API route returns JSON
- 404 and 405 behavior
- session login and logout
- CSRF rejection and acceptance
- database connectivity
- cache read/write
- rate-limit rejection
- exception logs and generic production 500 output
