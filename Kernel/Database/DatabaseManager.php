<?php
namespace Kernel\Database;

use PDO;
use PDOException;

/**
 * Class DatabaseManager
 * TODO: prettify SQL query syntax
 * @package Kernel\Database
 */
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


    public function makeDatabase()
    {
        // has any tables or not
        $query = $this->pdo->query("SHOW TABLES");
        $tables =  $query->fetchAll(PDO::FETCH_COLUMN);

        if(count($tables) <= 0)
            // make database migration
            $this->pdo->exec(@file_get_contents(__DIR__ . "/../../schema/database.sql") ?? "");
    }

    public function registerUser(string $username): string
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO users (username, session_key, score, current_page) VALUES(?, ?, 0, '');"
        );

        $session_key = sha1(rand() . time());

        $result = $statement->execute([
            $username,
            $session_key
        ]);

        return $session_key;
    }

    public function submitMove(string $sessionKey, int $applyMoves, string $newPage)
    {
        $statement = $this->pdo->prepare(
            "UPDATE users
SET score=?, current_page=?
WHERE session_key = ?;
"
        );
        $statement->bindParam(1, $applyMoves);
        $statement->bindParam(2, $newPage);
        $statement->bindParam(3, $sessionKey);
        $statement->execute();
        $res = $statement->fetchAll();
        return (is_bool($res)) ? [] : $res;
    }

    public function nextPlayer(int $notId, string $endPage)
    {
        $notId++;
        if ($notId > 4) $notId = 0;

        $statement = $this->pdo->prepare(
            "SELECT * FROM users WHERE id >= ? AND current_page != ? ORDER BY id ASC LIMIT 1"
        );
        $statement->bindParam(1, $notId, PDO::PARAM_INT);
        $statement->bindParam(2, $endPage);
        $statement->execute();
        $res = $statement->fetch();
        return (is_bool($res)) ? [] : $res;
    }


    public function switchPlayer(int $curPlayerId)
    {
        $statement = $this->pdo->prepare(
            "UPDATE game
SET current_player_id=?
WHERE id = 1;
"
        );
        $statement->bindParam(1, $curPlayerId);
        $statement->execute();
        $res = $statement->fetchAll();
        return (is_bool($res)) ? [] : $res;
    }


    public function getGame(): array|null
    {
        $stat = $this->pdo->prepare(
            "SELECT * FROM game LIMIT 1"
        );
        $stat->execute();
        $res = $stat->fetch();
        return (is_bool($res)) ? [] : $res;
    }

    public function getPlayers(): array|null
    {
        $stat = $this->pdo->prepare(
            "SELECT * FROM users LIMIT 4"
        );
        $stat->execute();
        $res = $stat->fetchAll();
        return (is_bool($res)) ? [] : $res;
    }

    public function getPlayerBySessionKey(string $sessionKey): array|null
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM users WHERE session_key = ? LIMIT 1"
        );
        $statement->bindParam(1, $sessionKey);
        $statement->execute();
        $res = $statement->fetch();
        return (is_bool($res)) ? [] : $res;
    }

    public function getPlayerByID(int $id): array|null
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM users WHERE id = ? LIMIT 1"
        );
        $statement->bindParam(1, $id, PDO::PARAM_INT);
        $statement->execute();
        $res = $statement->fetch();
        return (is_bool($res)) ? [] : $res;
    }

    public function createGame(
        string $pageStart,
        string $pageEnd,
        int $serverCountTransactions
    ): array|null
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO game 
    (page_start, page_end, server_count_transitions, current_player_id) 
    VALUES (?, ?, ?, 1);"
        );
        $statement->bindParam(1, $pageStart);
        $statement->bindParam(2, $pageEnd);
        $statement->bindParam(3, $serverCountTransactions);
        $statement->execute();
        $res = $statement->fetchAll();
        return (is_bool($res)) ? [] : $res;
    }

    public function resetGame()
    {
        $sql = [
            "DELETE FROM users WHERE 1=1; ",
            "DELETE FROM game WHERE 1=1; ",
            "ALTER TABLE users AUTO_INCREMENT = 1; ",
            "ALTER TABLE game AUTO_INCREMENT = 1; "
        ];

        foreach($sql as $row)
            $this->pdo->prepare(
                $row
            )->execute();
    }

}