<?php
require_once "init.php";

//получение из базы списка категорий
$sql_category = "SELECT id, name FROM category;";
$result = mysqli_query($con, $sql_category);
if ($result) {
    $category = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($con);
}

//$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];




