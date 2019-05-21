<?php
// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];
date_default_timezone_get("Europe/Moscow");

$is_auth = (bool) rand(0, 1);
$user_name = 'говнован';
$user_avatar = 'img/user.jpg';
$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$product = [
    ["lot-name"=> "2014 Rossingol District Snowboard", "category"=>$category[0], "lot-price"=> 10999, "picture"=> 'img/lot-1.jpg'],
    ["lot-name"=> "DC Ply Mens 2016/2017 Snowboard", "category"=>$category[0], "lot-price"=> 159999, "picture"=> "img/lot-2.jpg"],
    ["lot-name"=> "Крепления Union Contact Pro 2015 года размер L/XL", "category"=>$category[2], "lot-price"=> 8000, "picture"=> "img/lot-3.jpg"],
    ["lot-name"=> "Ботинки для сноуборда DC Mutiny Charocal", "category"=>$category[1], "lot-price"=> 10999, "picture"=> "img/lot-4.jpg"],
    ["lot-name"=> "Куртка для сноуборда DC Mutiny Charocal", "category"=>$category[3], "lot-price"=> 7500, "picture"=> "img/lot-5.jpg"],
    ["lot-name"=> "Маска Oakley Kanopu", "category"=>$category[5], "lot-price"=> 5400, "picture"=> "img/lot-6.jpg"]
];


