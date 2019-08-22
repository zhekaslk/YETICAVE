<?php
//страница со списком посещенных страниц
use lot\Lot;

require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
require_once "init.php";
session_start();
//получение из кук списка с id посещенных страниц. если таковые есть находим в базе список лотов по их id, записываем в массив и передаем в шаблон
if (isset($_COOKIE['history'])) {
    $lot = Lot::lotViewHistory($_COOKIE['history']);
    if (empty($lot)) {
        $main_content = templating("templates/history.php", ["message" => "История просмотров пуста", "category" => $category]);
    } else {
        $lot = Lot::checkLotStatus($lot);
        $main_content = templating("templates/history.php", ["message" => "История просмотров", "lot" => $lot, "category" => $category]);
    }
} else {
    $main_content = templating("templates/history.php", ["message" => "История просмотров пуста", "category" => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "История просмотров", "main_content" => $main_content, "category" => $category]);
print $layout_content;