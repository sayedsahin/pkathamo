# Directory Structure

```text
app/
├── Controllers/
├── Helpers/
├── Middlewares/
├── Models/
├── Supports/
├── Systems/
├── Validation/
└── Views/
bootstrap/
config/
database/
public/
storage/
bin/
vendor/
```

## `app/Controllers`

HTTP controllers. Controller methods may return a `Response`, return a redirect response, or render a view.

## `app/Helpers`

Globally autoloaded helper functions such as `request()`, `response()`, `db()`, `cache()`, `config()`, `view()` and `e()`.

## `app/Middlewares`

Application middleware implementations such as CSRF protection, authentication, security headers and rate limiting.

## `app/Models`

Query Builder subclasses that define a table and may contain application-specific query methods.

## `app/Supports`

Application services that sit above the framework systems, including authentication, roles, rate limiting and request-scoped context.

## `app/Systems`

Core framework components: container, database, Query Builder, HTTP request/response, sessions, cache, configuration, middleware and exception handling.

## `app/Validation`

Validator and validation exception classes.

## `app/Views`

Plain PHP templates. Dot notation maps to nested directories.

## `bootstrap`

Small bootstrap scripts for configuration, sessions, cache, authentication, the container and routing.

## `config`

Application configuration. Each cacheable configuration file returns an array. `routes.php` and `path.php` are loaded separately.

## `database`

Database schema or backup files.

## `public`

The only directory that should be publicly accessible. It contains `index.php`, public assets and web-server rewrite rules.

## `storage`

Generated cache and runtime files. Generated `config.php` and `route.cache` must not be distributed as release artifacts.

## `bin`

Command-line scripts for rebuilding configuration and route caches.
