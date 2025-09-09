<?php

namespace Game;

interface Database
{
    public function __construct(string $host, string $database, string $user, string $password);

    public function insert(string $table, array $data);

    public function update(string $table, array $data, array $conditions);

    public function delete(string $table, array $conditions);

    public function select(array $tableColums, array $conditions): array;

    public function testConnection(): bool;
}