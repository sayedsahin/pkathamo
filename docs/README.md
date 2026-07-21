# Pkathamo Documentation

Pkathamo is a lightweight, sync-first, performance-focused PHP framework designed for the standard PHP-FPM or Apache request lifecycle.

This documentation describes the 1.x application structure and public APIs.

## Documentation map

### Getting started

- [Introduction](01-introduction.md)
- [Installation](02-installation.md)
- [Directory structure](03-directory-structure.md)
- [Request lifecycle](04-request-lifecycle.md)
- [Configuration](05-configuration.md)

### HTTP layer

- [Routing](06-routing.md)
- [Controllers](07-controllers.md)
- [Request](08-request.md)
- [Responses and redirects](09-responses-and-redirects.md)
- [Views and helpers](10-views-and-helpers.md)
- [Middleware](11-middleware.md)
- [Validation](12-validation.md)

### Data and services

- [Database](13-database.md)
- [Query Builder](14-query-builder.md)
- [Models](15-models.md)
- [Service container](16-container.md)
- [Sessions and cookies](17-sessions-and-cookies.md)
- [Authentication and roles](18-authentication-and-roles.md)
- [API authentication](19-api-authentication.md)
- [Cache](20-cache.md)
- [Rate limiting](21-rate-limiting.md)

### Production

- [Exception handling](22-exception-handling.md)
- [Security](23-security.md)
- [Command-line tools](24-command-line-tools.md)
- [Deployment](25-deployment.md)
- [Performance](26-performance.md)
- [Troubleshooting](27-troubleshooting.md)

## Conventions

- Examples assume the Composer autoloader is loaded through `public/index.php`.
- Application classes use the `App\\` namespace.
- API requests are identified by the `/api` path prefix.
- Query values are bound through PDO. SQL identifiers and raw SQL must remain developer-controlled.
- Terminal Query Builder methods execute the query and reset query-specific state.
