<?php
//отображение списка лотов по категории
require_once "vendor/autoload.php";
require_once ("init.php");
require_once ("functions.php");
require_once ("data.php");
session_start();

if (isset($_GET['category']) AND $_GET['category'] > 0 AND $_GET['category'] < 7) {
    $sql = "SELECT lot.*,  category.name as cat_name FROM lot
  JOIN category ON lot.id_category = category.id
WHERE lot.id_category = ? ORDER BY lot.create_date DESC";
    $stmt = db_get_prepare_stmt($con, $sql, [$_GET['category']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    return http_response_code(404);
}
//var_dump($lot);
$main_content = templating("templates/all-lots.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Категория",  "main_content" => $main_content, "category" => $category]);
print $layout_content;