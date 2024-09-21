<?php

namespace App\Contracts;

interface DatabaseInterface
{
    // connects to database
    public function connect(?string $connectionString): void;

    // executes query and returns number of affected rows
    public function execute(string $query): int;

    // executes query and returns fetch result
    public function fetch(string $query): array;

    // prepares, executes query and returns fetch results
    public function prepare(string $query, array $args): array;

    // transaction
    public function transaction(string $query, array $args): void;
}
