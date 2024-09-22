<?php

namespace App\Adapters\Database;

use App\Contracts\DatabaseInterface;
use Exception;

abstract class Pdo implements DatabaseInterface
{
    protected ?\PDO $pdo;

    abstract public function connect(?string $connectionString = null): void;

    public function fetch(string $query): array
    {
        return $this->pdo->query($query, \PDO::FETCH_ASSOC)->fetchAll();
    }

    /**
     * @throws Exception
     */
    public function transaction(string $query, array $args): void
    {
        $statement = $this->pdo->prepare($query);
        try {
            $this->pdo->beginTransaction();
            foreach ($args as $row) {
                $statement->execute($row);
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function prepare(string $query, array $args): array
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($args);

        return $statement->fetchAll();
    }

    public function execute(string $query): int
    {
        return $this->pdo->exec($query);
    }

    protected function pdoConnect(string $connectionString): void
    {
        $this->pdo = new \PDO($connectionString);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
