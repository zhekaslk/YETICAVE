<?php
//отображение списка лотов по категории
require_once "vendor/autoload.php";
require_once ("init.php");
require_once ("functions.php");
require_once ("data.php");
session_start();
$category_id = [1,2,3,4,5,6];
if (in_array($_GET['category'], $category_id)) {
    $sql = "SELECT lot.*,  category.name as cat_name, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE lot.id_category = ? AND end_date > NOW() ORDER BY lot.create_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['category']]);
    $lot = $stmt->fetchAll();
}
else {
    return http_response_code(404);
}
//var_dump($lot);
$main_content = templating("templates/all-lots.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Категория",  "main_content" => $main_content, "category" => $category]);
print $layout_content;