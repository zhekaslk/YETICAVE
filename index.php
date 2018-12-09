<?php
$is_auth = (bool) rand(0, 1);
$user_name = 'Иван';
$user_avatar = 'img/user.jpg';
$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$product = [
    ["name"=> "2014 Rossingol District Snowboard", "category"=>$category[0], "price"=> 10999, "picture"=> 'img/lot-1.jpg'],
    ["name"=> "DC Ply Mens 2016/2017 Snowboard", "category"=>$category[0], "price"=> 159999, "picture"=> "img/lot-2.jpg"],
    ["name"=> "Крепления Union Contact Pro 2015 года размер L/XL", "category"=>$category[2], "price"=> 8000, "picture"=> "img/lot-3.jpg"],
    ["name"=> "Ботинки для сноуборда DC Mutiny Charocal", "category"=>$category[1], "price"=> 10999, "picture"=> "img/lot-4.jpg"],
    ["name"=> "Куртка для сноуборда DC Mutiny Charocal", "category"=>$category[3], "price"=> 7500, "picture"=> "img/lot-5.jpg"],
    ["name"=> "Маска Oakley Kanopu", "category"=>$category[5], "price"=> 5400, "picture"=> "img/lot-6.jpg"]
];
date_default_timezone_get("Europe/Moscow");
require_once "functions.php";
$main_content = templating("templates/index.php", ["product" => $product]);
$layout_content = templating("templates/layout.php", ["page_name" => "Главная", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;

?>
