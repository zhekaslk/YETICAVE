<?php
//главная страница сайта
use classes\Lot;

require_once "vendor/autoload.php";
require_once "functions.php";
require_once "init.php";
require_once "data.php";
require_once "getwinner.php";
session_start();

//получение списка лотов, актуальных на данный момент
$lot = Lot::actualLotList();
$main_content = templating("templates/index.php", ["product" => $lot, "category" => $category]);
if (isset($_SESSION["user"])) {
    $layout_content = templating("templates/layout.php", ["page_name" => "Главная", "user_avatar" => $_SESSION["user"]["avatar"], "user_name" => $_SESSION["user"]["name"], "main_content" => $main_content, "category" => $category]);
} else {
    $layout_content = templating("templates/layout.php", ["page_name" => "Главная", "main_content" => $main_content, "category" => $category]);
}
print $layout_content;