# Views and Helpers

## Views

Views are plain PHP files under `app/Views`.

```php
view('home', [
    'title' => 'Home',
    'users' => $users,
]);
```

Dot notation maps to folders:

```php
view('auth.login', ['title' => 'Login']);
```

This loads:

```text
app/Views/auth/login.php
```

## Escaping output

```php
<?= e($user->name) ?>
```

Render trusted HTML explicitly:

```php
<?= raw($trustedHtml) ?>
```

Do not pass untrusted user input to `raw()`.

## View paths

```php
$path = view_path('auth.login');
```

## CSRF fields

```php
<form method="post">
    <?= csrf_field() ?>
</form>
```

Get only the token:

```php
$token = csrf_token();
```

CSRF validation is handled by middleware; manual `verify_csrf()` calls are not required.

## Core helpers

```php
request();
response();
db();
cache();
session();
config();
env();
```

Role helpers:

```php
if (role('admin')) {
    // ...
}

if (roles(['admin', 'editor'])) {
    // User has any listed role.
}
```

## Debug helpers

```php
pr($value);
dd($value);
```

Do not leave `dd()` in production code.

## Legacy flash helpers

`flash()`, `back()` and `show_flash()` are available. New code should generally prefer response redirects:

```php
return response()->redirect('/')->with(['success' => 'Saved']);
```

## Text and time helpers

The starter includes helpers such as `textShorten()`, `number_formatting()`, `dateFormat()`, `time_ago()` and `duration()`.

## Image helper

`App\Supports\ImageHelper::resize()` uses GD to create a resized image resource. Verify the input format and GD extension before using it in upload workflows.
