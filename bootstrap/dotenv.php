<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

$envFile = ROOT_PATH . '/.env';

if (!is_file($envFile)) {
    return;
}

$dotenv = new Dotenv();
$dotenv->usePutenv();
$dotenv->load($envFile);
