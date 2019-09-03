<?php
//страница отображения лота

use classes\Lot;
use classes\Rate;

require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
session_start();

$lot_id = filter_var($_GET['lot_id'], FILTER_VALIDATE_INT);
if (!$lot_id) {
    return http_response_code(404);
}
$lot = Lot::lotInfo($lot_id);
if (empty($lot)) {
    return http_response_code(404);
}
//блок отображения истории торгов (все ставки к данному лоту)
$rates = Rate::lotRates($lot_id);
//форма добавления ставки
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = Rate::checkAddRate($_POST, $lot);
    if (count($errors)) {
        $main_content = templating("templates/lot.php", ['category' => $category, 'errors' => $errors, "lot" => $lot, "rates" => $rates]);
    } else {
        Rate::addRate($lot_id, $_POST);
    }
} else {
    $main_content = templating("templates/lot.php", ["lot" => $lot, "rates" => $rates]);
}
set_cookie($lot_id);
$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "main_content" => $main_content, "category" => $category]);
print $layout_content;