<?php
//поиск лотов по названию и описанию
require_once "vendor/autoload.php";
require_once ("init.php");
require_once ("functions.php");
require_once ("data.php");
session_start();

$search = $_GET['search'];
// запрос на поиск лотов по имени или описанию

if ($search) {
    $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
//var_dump($lot);
$main_content = templating("templates/search.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Поиск",  "main_content" => $main_content, "category" => $category]);
print $layout_content;