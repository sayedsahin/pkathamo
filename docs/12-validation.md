# Validation

Create a validator from an array:

```php
use App\Validation\Validator;

$validator = Validator::make(request()->all())
    ->required(['name', 'email'])
    ->string(['name', 'email'])
    ->email('email');
```

## Reading results

```php
if ($validator->fails()) {
    $errors = $validator->errors();
}

$data = $validator->validated();
```

`validated()` returns only fields that participated in validation rules. It throws `ValidationException` when validation failed.

## Try/catch flow

```php
try {
    $data = Validator::make(request()->json())
        ->required(['email', 'password'])
        ->email('email')
        ->validated();
} catch (\App\Validation\ValidationException $e) {
    return response()->json(['errors' => $e->errors()], 422);
}
```

## Rules

### `required()`

Rejects missing values, `null`, empty strings, whitespace-only strings and empty arrays.

```php
->required(['name', 'email'])
```

### `nullable()`

Allows `null` for later rules.

```php
->nullable('phone')->string('phone')
```

### Type rules

```php
->string('name')
->int('age')
->bool('active')
->email('email')
```

`int()` and `bool()` are strict. HTML form values are strings, so cast or normalize them before strict type validation. JSON numbers and booleans retain their native types.

### Length rules

```php
->min('password', 8)
->max('name', 100)
```

These rules use `mb_strlen()` on the string representation.

### Allowed values

```php
->in('status', ['draft', 'published'])
```

### Confirmation

```php
->confirmed('password')
```

This compares `password` with `password_confirmation`. The confirmation field is used for comparison but is not automatically added to the validated result unless another rule registers it.

### Conditional required rule

```php
->sometimes('company_name', function (array $data): bool {
    return ($data['account_type'] ?? null) === 'business';
})
```

### Custom validation

```php
->custom(function (Validator $validator): void {
    // Compose application-specific checks.
})
```

## Bail

```php
->bail()
```

The validator throws immediately after the first failure.
