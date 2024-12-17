<?php

namespace App\Classes;

use PDO;
use PDOException;

class Database
{
    private ?PDO $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new PDO('mysql:host=localhost;dbname=sakila', 'root', '');
        } catch (PDOException $ex) {
            die('Database connection failed' . $ex->getMessage());
        }
    }

    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    public function closeConnection(): void
    {
        $this->connection = null;
    }
}
