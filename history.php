<?php
//страница со списком посещенных страниц
require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
require_once "init.php";
session_start();
//получение из кук списка с id посещенных страниц. если таковые есть находим в базе список лотов по их id, записываем в массив и передаем в шаблон
if (isset($_COOKIE['history'])) {
    $lot_id = strip_tags(htmlspecialchars($_COOKIE['history']));
    if ($_SESSION) {
        $id_user = $_SESSION["user"]["id"];
        $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner = $id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
 FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
    } else {
        $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
 FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
    }
    $lot = $pdo->query($sql)->fetchAll();
    if (empty($lot)) {
        $main_content = templating("templates/history.php", ["message" => "История просмотров пуста", "category" => $category]);
    } else {
        $lot = check_lot_status($lot);
        $main_content = templating("templates/history.php", ["message" => "История просмотров", "lot" => $lot, "category" => $category]);
    }
} else {
    $main_content = templating("templates/history.php", ["message" => "История просмотров пуста", "category" => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "История просмотров", "main_content" => $main_content, "category" => $category]);
print $layout_content;