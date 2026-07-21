# Exception Handling

Pkathamo registers a central exception, error and shutdown handler after configuration is loaded and before sessions, cache, authentication, middleware and routing.

## Successful request overhead

The handler only registers callbacks. Stack formatting, logging and rendering run only when an error occurs. The shutdown callback performs a small `error_get_last()` check.

## PHP errors

Reportable PHP errors are converted to `ErrorException`. Errors suppressed by the current `error_reporting()` mask are not converted.

## Uncaught exceptions

The handler:

1. prevents recursive handling
2. clears output buffers
3. writes the full exception to PHP's error log
4. renders CLI, API or web output
5. exits with code 1 in CLI mode

## Production output

With debug disabled:

Web:

```html
<h1>Internal Server Error</h1>
```

API:

```json
{"error":"Internal Server Error"}
```

CLI:

```text
Internal Server Error
```

## Debug output

With debug enabled, API and HTML responses include exception details and a stack trace. Never enable debug output on a public production server.

## Fatal errors

The shutdown handler checks fatal error types and converts them to the same central response flow when possible.

## Logging

The built-in handler uses:

```php
error_log((string) $exception);
```

Configure PHP's `error_log` destination in production. A future logger service can be added without changing the normal exception flow.

## Avoid duplicate handling

Do not wrap every controller or middleware in a broad `try/catch` only to produce a generic 500. Catch exceptions locally only when the application can recover or return a meaningful domain response.
