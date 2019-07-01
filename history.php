<?php

require_once "functions.php";
require_once "data.php";
require_once "init.php";
session_start();
//получение из кук списка с id посещенных страниц


if (isset($_COOKIE['history'])) {
        $lot_id = implode(",", json_decode($_COOKIE['history']));
        $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
        $result = mysqli_query($con, $sql);
        $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $main_content = templating("templates/history.php", ["message" => "История просмотров", "lot" => $lot, "category" => $category]);
}
else {
    $main_content = templating("templates/history.php", ["message" => "История просмотров пуста", "category" => $category]);
}

var_dump($lot_id);

$layout_content = templating("templates/layout.php", ["page_name" => "История просмотров", "main_content" => $main_content, "category" => $category]);
print $layout_content;