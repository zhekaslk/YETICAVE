<?php



require_once "functions.php";
require_once "data.php";
session_start();
$main_content = templating("templates/index.php", ["product" => $product]);
if (isset($_SESSION["user"])) {
    $layout_content = templating("templates/layout.php", ["page_name" => "Главная", "user_avatar" => $user_avatar, "user_name" => $_SESSION["user"]["name"], "main_content" => $main_content, "category" => $category]);
}
else {
    $layout_content = templating("templates/layout.php", ["page_name" => "Главная", "main_content" => $main_content, "category" => $category]);
}
print $layout_content;

