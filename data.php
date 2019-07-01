<?php
require_once "init.php";

$sql_category = "SELECT id, name FROM category;";
$result = mysqli_query($con, $sql_category);
if ($result) {
    $category = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($con);
}



$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];
date_default_timezone_get("Europe/Moscow");

$is_auth = (bool) rand(0, 1);
$user_name = 'говнован';
$user_avatar = 'img/user.jpg';
//$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];




