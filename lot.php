<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 10.12.18
 * Time: 9:48
 */


require_once "functions.php";
require_once "data.php";

$lot_id = $_GET['lot_id']-1;

if(!array_key_exists($lot_id, $product)) {
    return http_response_code(404);
}
$lot = $product[$lot_id];
/////// запись в куки id посещенных страниц
$expire = strtotime("+30 days");
$counter_name = "history";
$counter_value = [];
$expire = strtotime("+30 days");
$path = "/";
if (isset($_COOKIE['history'])) {
    $counter_value = json_decode($_COOKIE['history']);
    if (!in_array($lot_id, $counter_value))
    $counter_value [] = $lot_id;
}
setcookie($counter_name, json_encode($counter_value), $expire, $path);

$main_content = templating("templates/lot.php", ["lot" => $lot]);
$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;