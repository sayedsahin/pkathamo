<?php
// Not use anymore

// Command: php bin/cache-env.php
/* require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$values = $dotenv->parse(file_get_contents(__DIR__ . '/../.env'));

foreach ($values as $key => $val) {
    $values[$key] = castEnvValue($val);
    $_ENV[$key] = $val;
}

$cacheFile = __DIR__ . '/../storage/cache/env.php';

file_put_contents(
    $cacheFile,
    '<?php return ' . var_export($values, true) . ';'
);

if (php_sapi_name() === 'cli') {
    echo "Environment cached to: $cacheFile\n";
}

function castEnvValue($value)
{
    $lower = strtolower($value);

    if ($lower === 'true')  return true;
    if ($lower === 'false') return false;
    if ($lower === 'null')  return null;

    // integer
    if (ctype_digit($value)) return (int) $value;

    // float
    if (is_numeric($value)) return (float) $value;

    return $value; // default: string
} */

