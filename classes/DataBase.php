<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 03.09.19
 * Time: 5:18
 */

namespace classes;

use PDO;
use PDOException;

class DataBase
{
    public static function databaseConnect()
    {
        $host = "localhost";
        $user = "phpmyadmin";
        $password = "seaways17";
        $db = "yeticave";
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $password, $opt);
        } catch (PDOException $e) {
            echo "Cоединение прервано!" . $e->getMessage();
        }
        return $pdo;
    }

    public static function searchInDatabase($sql, array $arg = [])
    {
        $pdo = self::databaseConnect();

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arg);
            $result = $stmt->fetchAll();
        }
        catch (PDOException $e) {
            echo "Ошибка запроса!" . $e->getMessage();
        }
        return $result;
    }

    public static function udpateDatabase($sql, array $arg = [])
    {
        try {
            $pdo = self::databaseConnect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arg);
        }
        catch (PDOException $e) {
            echo "Ошибка запроса!" . $e->getMessage();
        }
        return $stmt;
    }

}