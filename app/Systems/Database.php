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
        $db_config = config('database.connections.mysql');
        try {
            $dsn = $db_config['driver']
                . ":host=" . $db_config['host']
                . ";port=" . $db_config['port']
                . ";dbname=" . $db_config['database']
                . ";charset=". $db_config['charset'];

            $this->pdo = new PDO(
                $dsn,
                $db_config['username'],
                $db_config['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT         => $db_config['options']['persistent'],
                ]
            );

        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed", 0, $e);
        }
    }
}
