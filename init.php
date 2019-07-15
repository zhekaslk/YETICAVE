<?php
//совственно подключение к базе
require_once 'functions.php';
require_once "vendor/autoload.php";

$host = "localhost";
$user = "phpmyadmin";
$password = "seaways17";
$db = "yeticave";
$con = mysqli_connect($host, $user, $password, $db);
