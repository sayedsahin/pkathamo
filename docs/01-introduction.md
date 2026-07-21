# Introduction

**Pkathamo** is a lightweight PHP framework built around three core principles: **Lightweight**, **Fast** and **Simple**.

It provides the essential features required for modern web applications while keeping the request lifecycle direct, predictable and easy to understand.

Pkathamo is designed for developers who need more than a minimal router but do not want the complexity, hidden behavior or runtime overhead of a large full-stack framework.

## Core Philosophy

- **Performance First** – Every design decision prioritizes fast execution, low memory usage and minimal request-time work.
- **Simple by Design** – Clean APIs and a clear application structure let developers focus on application logic instead of framework internals.
- **Efficient Development** – Practical built-in tools reduce repetitive code without taking control away from the developer.
- **Minimal Overhead** – Services and drivers are initialized only when needed, avoiding unnecessary work during every request.
- **Predictable Behavior** – Important operations remain explicit, with minimal magic, hidden state or deeply nested abstractions.
- **Developer Friendly** – Familiar PHP patterns, readable source code and straightforward configuration make the framework easy to learn and debug.

## What Makes Pkathamo Different

Pkathamo combines a lightweight runtime with the practical features commonly required by production applications.

A minimal framework may provide only routing and responses, requiring developers to assemble authentication, validation, sessions, caching and rate limiting separately.

A large full-stack framework may provide those features through a more complex boot process, service lifecycle, ORM layer and abstraction stack.

Pkathamo takes a focused approach:

```text
Lightweight core
+
Essential application features
+
Direct and predictable execution
```


## Main components

| Component | Purpose |
|---|---|
| FastRoute | HTTP route matching and route cache |
| Request | GET, POST, files, cookies, JSON, headers and request metadata |
| Response | HTML, JSON, headers, status and redirects |
| MiddlewareKernel | Global and route middleware execution |
| QueryBuilder | Fluent PDO query construction and raw SQL execution |
| Validator | Fluent validation with filtered validated data |
| Session | Native or null session driver facade |
| Auth | Session and bearer authentication state |
| Cache | Array, file, APCu, Redis or Memcached storage |
| RateLimiter | File, APCu, Redis or Memcached counters |
| ExceptionHandler | Central HTML, JSON and CLI error handling |
| Container | Constructor dependency resolution and singletons |