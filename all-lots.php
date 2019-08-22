<?php
//отображение списка лотов по категории
use lot\Lot;

require_once "vendor/autoload.php";
require_once("init.php");
require_once("functions.php");
require_once("data.php");
session_start();

$category_id = [1, 2, 3, 4, 5, 6];
if (in_array($_GET['category'], $category_id)) {
    $lot = Lot::lotsByCatagory($_GET['category']);
} else {
    return http_response_code(404);
}
$main_content = templating("templates/all-lots.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Категория", "main_content" => $main_content, "category" => $category]);
print $layout_content;