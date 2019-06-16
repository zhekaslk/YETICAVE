<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 18.05.19
 * Time: 3:05
 */
require_once "functions.php";
require_once "data.php";
//получение из кук списка с id посещенных страниц
$lot_id = json_decode($_COOKIE['history']);
foreach ($lot_id as $value) {
    $lot_history[] = $product[$value];
}
$main_content = templating("templates/history.php", ["lot_history" => $lot_history]);
$layout_content = templating("templates/layout.php", ["page_name" => "История просмотров", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;