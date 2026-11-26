
<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    // Store the single Database instance
    private static ?Database $instance = null;

    // Store the PDO connection
    private PDO $connection;

    // Private constructor prevents direct object creation
    private function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';

        $host = $config['host'];
        $dbname = $config['dbname'];
        $username = $config['username'];
        $password = $config['password'];
        $charset = $config['charset'];

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            $this->connection = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new PDOException(
                "Database connection failed: " . $e->getMessage()
            );
        }
    }

    // Return the single Database instance
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Return the PDO connection
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    // Prevent cloning
    private function __clone()
    {
    }

    // Prevent unserialization
    public function __wakeup(): void
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}
