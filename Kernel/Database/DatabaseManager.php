<?php
namespace Kernel\Database;

use PDO;
use PDOException;

class DatabaseManager
{
    private PDO $pdo;

    /**
     * DatabaseManager constructor.
     */
    public function __construct()
    {
    }

    /**
     * Try to connect to database
     * @param string $host Database host address (ex. 127.0.0.1)
     * @param string $databaseName Database name (ex. project)
     * @param string $user Database user (ex. user1)
     * @param string $password Database password (ex. password)
     * @param string $charset Database charset used in packets (ex. utf8)
     * @return bool Whether the connection is successful
     */
    public function setupConnection(
        string $host,
        string $port,
        string $databaseName,
        string $user,
        string $password,
        string $charset = "utf8"
    ): bool
    {
        $dsn = "mysql:host=$host;port=$port;dbname=$databaseName;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $password, $opt);
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}