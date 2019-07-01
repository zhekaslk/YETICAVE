<?php



require_once "functions.php";
require_once "init.php";
require_once "data.php";
session_start();

$sql_lots = "SELECT lot.id, lot.name, price, img, create_date, COUNT(rate.id), category.name as category FROM lot
LEFT JOIN rate ON rate.id_lot = lot.id
JOIN category ON lot.id_category = category.id
WHERE end_date > NOW()
GROUP BY lot.id
ORDER BY create_date DESC";
$result = mysqli_query($con, $sql_lots);
if ($result) {
    $product = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($con);
}


$main_content = templating("templates/index.php", ["product" => $product, "category" => $category]);
if (isset($_SESSION["user"])) {
    $layout_content = templating("templates/layout.php", ["page_name" => "Главная", "user_avatar" => $_SESSION["user"]["avatar"], "user_name" => $_SESSION["user"]["name"], "main_content" => $main_content, "category" => $category]);
}
else {
    $layout_content = templating("templates/layout.php", ["page_name" => "Главная", "main_content" => $main_content, "category" => $category]);
}
var_dump($product);
print $layout_content;

