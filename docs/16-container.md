# Service Container

The container supports normal bindings, singletons, closures and automatic constructor injection with cached reflection metadata.

## Configuration

`config/container.php`:

```php
return [
    'singletons' => [
        \App\Systems\Database::class,
    ],
    'bindings' => [],
];
```

## Singleton

Use a singleton when one instance should be reused during a request:

```php
'singletons' => [
    \App\Systems\Database::class,
    \App\Services\FileLogger::class,
],
```

Do not register stateful per-query objects such as `QueryBuilder` or reusable response objects as singletons.

## Interface binding

```php
'bindings' => [
    \App\Contracts\LoggerInterface::class
        => \App\Services\FileLogger::class,
],
```

Constructor:

```php
final class AuditService
{
    public function __construct(private LoggerInterface $logger)
    {
    }
}
```

## Closure binding

Programmatic registration:

```php
$container->singleton(ApiClient::class, function ($container) {
    return new ApiClient(config('services.api.key'));
});
```

## Resolve manually

```php
$service = $container->make(ReportService::class);
```

## Resolution rules

- Class-typed constructor parameters are recursively resolved.
- Built-in parameters require default values.
- Non-instantiable classes require a binding.
- ReflectionClass instances are cached.

Keep the container small. It is a dependency-resolution tool, not a registry for every static framework component.
