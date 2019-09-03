<?php

use classes\User;
//страница для авторизации
require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
require_once "init.php";

session_start();
//проверка отправлена ла форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $res = User::checkLogin($_POST);
    $errors = $res["errors"];
    $auth = $res["auth"];
    if (count($errors)) {
        $main_content = templating("templates/login.php", ['category' => $category, 'errors' => $errors, 'auth' => $auth]);
    }
    else {
        $_SESSION['user'] = $auth;
        header("Location: /index.php");
        exit();
    }
} else {
    $main_content = templating("templates/login.php", ['category' => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Главная", "main_content" => $main_content, "category" => $category]);
print $layout_content;