<?php
//СТРАНИЦА ДОБАВЛЕНИЯ ЛОТА

use classes\Lot;

require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
session_start();

//проверка чтобы пользователь был авторизован
if (empty($_SESSION["user"])) {
    return http_response_code(403);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $res = Lot::checkAddLot($_POST, $_FILES["lot-img"]);
    $add_lot = $res["add_lot"];
    $errors = $res["errors"];
    if (count($errors)) {
        $main_content = templating("templates/add.php", ['add_lot' => $add_lot, 'category' => $category, 'errors' => $errors]);
    } else {
        Lot::addLot($add_lot);
    }
} else {
    $main_content = templating("templates/add.php", ['category' => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Добавь", "main_content" => $main_content, "category" => $category]);
print $layout_content;