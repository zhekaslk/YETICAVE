<?php
require_once "init.php";
require_once "vendor/autoload.php";

//получение из базы списка категорий
$sql_category = "SELECT * FROM category ORDER BY id ASC ;";
$category = $pdo->query($sql_category)->fetchAll();