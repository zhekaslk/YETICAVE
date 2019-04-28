<?php



require_once "functions.php";
require_once "data.php";

$main_content = templating("templates/index.php", ["product" => $product]);
$layout_content = templating("templates/layout.php", ["page_name" => "Главная", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;

