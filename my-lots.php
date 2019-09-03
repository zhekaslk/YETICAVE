<?php
//отображение ставок пользователя
use classes\Rate;
use classes\Lot;

require_once "vendor/autoload.php";
require_once("init.php");
require_once("functions.php");
require_once("data.php");
session_start();

if (isset($_SESSION["user"])) {
    $lot = Rate::userRates();
    $lot = Lot::checkLotStatus($lot);
} else {
    header("Location: login.php");
}
$main_content = templating("templates/my-lots.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Мои ставки", "main_content" => $main_content, "category" => $category]);
print $layout_content;