<?php

namespace application\lib;

use PDO;
use PDOException;

class DataBase extends PDO
{
    protected $pdo;

    public function __construct()
    {
        $config = require "application/config/db.php";
        try {
            $this->pdo = new PDO("mysql:host=".$config["host"].";dbname=".$config["name"].";charset=".$config["charset"], $config["user"], $config["password"], $config["opt"]);
        } catch (PDOException $e) {
            echo "Cоединение прервано!" . $e->getMessage();
        }
    }

    public function getData($sql, array $arg = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($arg);
            $result = $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "Ошибка запроса!" . $e->getMessage();
        }
        return $result;
    }

    public function getRow($sql, array $arg = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($arg);
            $result = $stmt->fetchColumn();
        }
        catch (PDOException $e) {
            echo "Ошибка запроса!" . $e->getMessage();
        }
        return $result;
    }

    public function udpateDatabase($sql, array $arg = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($arg);
        }
        catch (PDOException $e) {
            echo "Ошибка запроса!" . $e->getMessage();
        }
        return $stmt;
    }

    public function getLastInsertId()
    {
        $id = $this->pdo->lastInsertId();
        return $id;
    }

}