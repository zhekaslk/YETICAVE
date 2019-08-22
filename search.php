<?php
//поиск лотов по названию и описанию
use lot\Lot;
require_once "vendor/autoload.php";
require_once("init.php");
require_once("functions.php");
require_once("data.php");
session_start();

// запрос на поиск лотов по имени или описанию
if ($_GET['search']) {
    $search = $_GET['search']; //filter_input(INPUT_GET, $_GET['search']);
    $lot = Lot::searchLot($search);
    if (empty($lot)) {
        $main_content = templating("templates/search.php", ["category" => $category, "message" => "По вашему запросу ничего не найдено"]);
    }
    else {
        $lot = Lot::checkLotStatus($lot);
        $main_content = templating("templates/search.php", ["lot" => $lot, "category" => $category, "search" => $search, "message" => "Результаты поиска по запросу "]);
    }
}
else {
    $main_content = templating("templates/search.php", ["category" => $category, "message" => "Вы ввели пустой поисковой запрос!"]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Поиск", "main_content" => $main_content, "category" => $category]);
print $layout_content;