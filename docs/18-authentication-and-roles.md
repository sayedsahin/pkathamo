# Authentication and Roles

## User resolver

`bootstrap/auth.php` registers how an authenticated user ID is converted into a user object:

```php
Auth::setResolver(function (int $id) {
    return db()->table('users')
        ->select('id', 'name', 'email', 'username')
        ->find($id);
});
```

Adjust selected columns to match your user schema and application needs.

## Login

```php
Auth::login((int) $user->id);
```

This regenerates the session ID, stores the authenticated user ID and clears request-level user cache.

## Authentication checks

```php
Auth::check();
Auth::id();
Auth::user();
```

The resolved user is cached in request-scoped context, so repeated `Auth::user()` calls do not repeatedly query the database.

## Stateless authentication state

Bearer middleware uses:

```php
Auth::once($userId, $resolvedUser);
```

This sets request-level authentication without writing a session.

## Logout

```php
Auth::logout();
```

This destroys the session, removes the remember cookie and clears request-level state.

## Remember-me authentication

The included middleware:

1. Reads the remember cookie.
2. Hashes the raw token.
3. Finds a non-expired database token.
4. Compares the user agent.
5. Regenerates the session.
6. Restores the user ID.
7. Rotates the token.

Store only token hashes in the database. Treat remember tokens as credentials.

## Roles

Get current roles:

```php
$roles = Role::userRoles();
```

Check one role:

```php
if (Role::has('admin')) {
    // ...
}
```

Check any or all:

```php
Role::any(['admin', 'editor']);
Role::all(['admin', 'verified']);
```

Assign or remove:

```php
Role::assign($userId, 'user');
Role::remove($userId, 'user');
```

Role lookups are cached for the current request and invalidated when roles are assigned or removed.

## Middleware

```php
[Authenticated::class]
```

```php
[RoleMiddleware::class, ['admin', 'editor']]
```

A missing authentication returns 401. An authenticated user without the required role receives 403.
