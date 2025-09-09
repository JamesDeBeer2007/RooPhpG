<?php

namespace Game;

use Exception;
use PDO;
use PDOException;

class Mysql implements Database
{
    private PDO $connection;

    public function __construct(string $host, string $database, string $user, string $password)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch(PDOException $error) {
            throw new PDOException($error->getMessage());
        }
    }

    public function insert(string $table, array $data): int
    {
        try {
            $columns = array_keys($data);
            $placeholders = ':' . implode(', :', $columns);
            $colomnList = implode(', ', $columns);
            $sql = "INSERT INTO {$table} ({$colomnList}) VALUES  ({$placeholders})";
            $statement = $this->connection->prepare($sql);
            foreach ($data as $key => $value) {
                $statement->bindValue(':' . $key, $value);
            }
            $statement->execute();
            return $this->connection->lastInsertId();
        } catch(PDOException $error) {
            throw new PDOException("Insert failed:". $error->getMessage());
        }
    }

    public function update(string $table, array $data, array $conditions): int
    {
        try{
            if(!isset($conditions['id']))
            {
                throw new Exception("Id is required for update");
            }

            $setClause = [];
            foreach($data as $column => $value) {
                $setClause[] = "{$column} = :{$column}";
            }
            $setClauseString = implode(', ', $setClause);
            $sql = "UPDATE {$table} SET {$setClauseString} WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            foreach ($data as $column => $value) {
                $statement->bindValue(":$column", $value);
            }
            $statement->bindValue(":id", $conditions['id']);
            $statement->execute();
            return $statement->rowCount();
        }catch(PDOException $error){
            throw new PDOException("Update failed:".$error->getMessage());
        }
    }

    public function delete(string $table, array $conditions): int
    {
        try {
            if (!isset($conditions['id']))
            {
                throw new Exception("No conditions provided");
            }
            $sql = "DELETE FROM {$table} WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(':id', $conditions['id']);
            $statement->execute();
            return $statement->rowCount();
        }catch(PDOException $error){
            throw new PDOException("Delete failed". $error->getMessage());
        }
    }

    public function select(array $tableColumns, array $conditions = []): array
    {
        try {

            $columns = [];
            foreach ($tableColumns as $table => $cols) {
                foreach ($cols as $col) {
                    if ($col === '*') {
                        $columns[] = "{$table}.*";
                    } else {
                        $columns[] = "{$table}.{$col}";
                    }
                }
            }

            $select = implode(', ', $columns);
            $tables = array_keys($tableColumns);
            $from = implode(', ', $tables);

            $query = "SELECT {$select} FROM {$from}";

            $whereConditions = [];
            $parameters = [];
            $paramCount = 0;
            if(!empty($conditions)) {
                foreach ($conditions as $key => $value) {
                    $paramName = "param".$paramCount++;

                    if(str_contains($key, ' LIKE'))
                    {
                        $columnName = str_replace(' LIKE', '', $key);
                        $whereConditions[] = "{$columnName} LIKE :{$paramName}";
                        $parameters[$paramName] = "%{$value}%";
                    }elseif(str_contains($key, ' BETWEEN'))
                    {
                        $columnName = str_replace(' BETWEEN', '', $key);
                        if(is_array($value) && count($value) === 2) {
                            $whereConditions[] = "$columnName BETWEEN :param{$paramCount} AND :param".$paramCount+1;
                            $parameters['param'.$paramCount++] = $value[0];
                            $parameters['param'.$paramCount] = $value[1];
                        }
                    }elseif(preg_match('/\s+[<>=!]+$/', $key))
                    {
                        $parts = preg_split('/\s+/', trim($key), 2);
                        $columnName = $parts[0];
                        $operator = $parts[1];
                        $whereConditions[] = "{$columnName} {$operator} :{$paramName}";
                        $parameters[$paramName] = $value;
                    }elseif(str_contains($key, '.') && str_contains($value, '.'))
                    {
                        $whereConditions[] = "$key = $value";
                    }else{
                        $whereConditions[] = "$key = :$paramName";
                        $parameters[$paramName] = $value;
                    }
                }
                if(!empty($whereConditions)) {
                    $query .= " WHERE ".implode(' AND ', $whereConditions);
                }
            }
            $statement = $this->connection->prepare($query);
            foreach($parameters as $name => $value) {
                $statement->bindValue(":$name", $value);
            }


            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $error){
            throw new PDOException("Error bij select query: ".$error->getMessage());
        }
    }


    public function testConnection(): bool
    {
        try {
            $test = $this->connection->query("SELECT 1");
            return $test !== false;
        }catch(PDOException $error){
            return false;
        }
    }


}