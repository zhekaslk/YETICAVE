<?php
//совственно подключение к базе
require_once 'functions.php';
require_once "vendor/autoload.php";

$host = "localhost";
$user = "phpmyadmin";
$password = "seaways17";
$db = "yeticave";
$charset = 'utf8';
$con = mysqli_connect($host, $user, $password, $db);

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $password, $opt);
} catch (Exception $exception) {
    echo "Сорян!";
}



