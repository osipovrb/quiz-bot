<?php

namespace App\Contracts;

interface DatabaseInterface
{
    /**
     * @param string|null $connectionString
     * @return void
     */
    public function connect(?string $connectionString): void;

    /**
     * @param string $query
     * @return int number of affected rows
     */
    public function execute(string $query): int;

    /**
     * @param string $query
     * @return array fetch result
     */
    public function fetch(string $query): array;

    /**
     * @param string $query query with placeholders
     * @param array $args params which will be put in place of placeholders
     * @return array fetch result
     */
    public function prepare(string $query, array $args): array;

    /**
     * Prepares query and executes it multiple times with set of params. If one
     * of executions fails then previous executions will be rolled back
     * @param string $query query with placeholders
     * @param array $args set of params to execute sequentially
     * @return void
     */
    public function transaction(string $query, array $args): void;
}
