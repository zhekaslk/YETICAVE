<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 10.12.18
 * Time: 9:48
 */


require_once "functions.php";
require_once "data.php";

$lot_id = $_GET['lot_id'];

if(!array_key_exists($lot_id, $product)) {
    return http_response_code(404);
}

$lot = $product[$lot_id];

$main_content = templating("templates/lot.php", ["lot" => $lot]);
$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;


