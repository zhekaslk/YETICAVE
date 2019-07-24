<?php
//отображение ставок пользователя
require_once "vendor/autoload.php";
require_once ("init.php");
require_once ("functions.php");
require_once ("data.php");
session_start();

if (isset($_SESSION["user"])) {
    $user_id = $_SESSION["user"]["id"];
    $sql = "SELECT rate.*, lot.name, lot.message, lot.img, lot.id_category, id_winner, users.contact, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state FROM rate
  JOIN lot ON lot.id = rate.id_lot
  JOIN users ON lot.id_author = users.id
  JOIN (SELECT MAX(rate.id) as last_rate FROM rate WHERE rate.id_user = 24 GROUP BY rate.id_lot) as l ON rate.id = last_rate
ORDER BY rate.date DESC";
    $result = mysqli_query($con, $sql);
    $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $lot = check_lot_status($lot);
}
else {
    header("Location: login.php");
}
//var_dump($lot);
$main_content = templating("templates/my-lots.php", ["lot" => $lot, "category" => $category]);
$layout_content = templating("templates/layout.php", ["page_name" => "Мои ставки",  "main_content" => $main_content, "category" => $category]);
print $layout_content;