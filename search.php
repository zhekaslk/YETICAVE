<?php
//поиск лотов по названию и описанию
require_once "vendor/autoload.php";
require_once("init.php");
require_once("functions.php");
require_once("data.php");
session_start();

$search = $_GET['search'];
// запрос на поиск лотов по имени или описанию

if ($search) {
    if ($_SESSION) {
        $id_user = $_SESSION["user"]["id"];
        $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = $id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
    } else {
        $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search]);
    $lot = $stmt->fetchAll();
    $lot = check_lot_status($lot);
}
$main_content = templating("templates/search.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Поиск", "main_content" => $main_content, "category" => $category]);
print $layout_content;