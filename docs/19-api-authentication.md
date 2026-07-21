# API Authentication

The starter application provides bearer-token authentication under `/api/auth/*`.

## Login flow

1. Validate JSON email and password.
2. Verify the password hash.
3. Generate a random 32-byte token.
4. Store its SHA-256 hash in `api_tokens`.
5. Return the raw token once to the client.

Client request:

```http
Authorization: Bearer RAW_TOKEN
```

## Protect a route

```php
$route->get('/api/auth/profile', [ApiAuthController::class, 'profile', [
    BearerAuth::class,
]]);
```

`BearerAuth` hashes the token, joins the token record to the user table, checks expiration and populates request-level authentication in one database query.

## Login example

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "secret"
}
```

Response:

```json
{
  "token": "raw-token",
  "user": {
    "id": 1,
    "name": "User",
    "email": "user@example.com",
    "roles": ["user"]
  }
}
```

## Logout

The logout endpoint hashes the current bearer token and deletes it from `api_tokens`.

## Registration and email verification

The starter generates a random verification token, stores a SHA-256 hash and expects the raw token in the verification URL.

```text
GET /api/auth/verify/{raw-token}
```

## Password reset request

The starter hashes reset tokens before storing them and sets an expiration time. Sending email and completing the password reset are application responsibilities.

## Token storage rules

- Never store a raw API, verification, reset or remember token when a one-way lookup hash is sufficient.
- Return or email the raw token only to the intended user.
- Use HTTPS in production.
- Add database indexes on token hash and expiration columns.
- Delete or rotate credentials after use.
