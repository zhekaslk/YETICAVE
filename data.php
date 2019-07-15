<?php
require_once "init.php";
require_once "vendor/autoload.php";

//получение из базы списка категорий
$sql_category = "SELECT * FROM category ORDER BY id ASC ;";
$result = mysqli_query($con, $sql_category);
if ($result) {
    $category = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($con);
}

//$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];




