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
            $dsn = DB_CONNECTION
                . ":host=" . DB_HOST
                . ";port=" . DB_PORT
                . ";dbname=" . DB_NAME
                . ";charset=utf8mb4";

            $this->pdo = new PDO(
                $dsn,
                DB_USERNAME,
                DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT         => true,
                ]
            );

        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed", 0, $e);
        }
    }
}
