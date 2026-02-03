<?php

return [
    'driver'   => $_ENV['SESSION_DRIVER'] ?? 'native',
    'lifetime' => (int) ($_ENV['SESSION_LIFETIME'] ?? 7200),
    'secure'   => (bool) ($_ENV['SESSION_SECURE'] ?? true),
    'samesite' => $_ENV['SESSION_SAMESITE'] ?? 'Lax',
];
