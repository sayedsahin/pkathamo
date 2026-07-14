<?php
namespace App\Systems;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    public PDO $pdo;

    public function __construct()
    {
        try {
            $dsn = config('database.connections.mysql.driver')
                . ":host=" . config('database.connections.mysql.host')
                . ";port=" . config('database.connections.mysql.port')
                . ";dbname=" . config('database.connections.mysql.database')
                . ";charset=". config('database.connections.mysql.charset', 'utf8mb4');

            $this->pdo = new PDO(
                $dsn,
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT         => config('database.connections.mysql.options.persistent', true),
                ]
            );

        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed", 0, $e);
        }
    }
}
