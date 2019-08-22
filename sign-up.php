<?php
//страница регистрации
use lot\Lot;

require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
require_once "init.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $res = Lot::checkSignUp($_POST, $_FILES["avatar"]);
    $sign_up = $res["sign_up"];
    $errors = $res["errors"];
    if (count($errors)) {
        $main_content = templating("templates/sign-up.php", ['category' => $category, 'errors' => $errors, 'sign_up' => $sign_up]);
    } else {
        Lot::addUser($sign_up);
        header("Location: /login.php");
        //echo "Регистрация прошла успешно. Вы будуте перенаправлены на страницу входа, где сможете войти используя свой email и пароль";
        exit();
    }
} else {
    $main_content = templating("templates/sign-up.php", ['category' => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Регистрация", "main_content" => $main_content, "category" => $category]);
print $layout_content;

