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
    ["name"=> "2014 Rossingol District Snowboard", "category"=>$category[0], "price"=> 10999, "picture"=> 'img/lot-1.jpg'],
    ["name"=> "DC Ply Mens 2016/2017 Snowboard", "category"=>$category[0], "price"=> 159999, "picture"=> "img/lot-2.jpg"],
    ["name"=> "Крепления Union Contact Pro 2015 года размер L/XL", "category"=>$category[2], "price"=> 8000, "picture"=> "img/lot-3.jpg"],
    ["name"=> "Ботинки для сноуборда DC Mutiny Charocal", "category"=>$category[1], "price"=> 10999, "picture"=> "img/lot-4.jpg"],
    ["name"=> "Куртка для сноуборда DC Mutiny Charocal", "category"=>$category[3], "price"=> 7500, "picture"=> "img/lot-5.jpg"],
    ["name"=> "Маска Oakley Kanopu", "category"=>$category[5], "price"=> 5400, "picture"=> "img/lot-6.jpg"]
];


