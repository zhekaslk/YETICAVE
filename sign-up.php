<?php

require_once "functions.php";
require_once "data.php";
require_once "userdata.php";

//session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sign_up = $_POST;
    $errors = [];
    $required = ["email", "password", "message", "name"];
    //проверка на заполненность полей
    foreach ($required as $value) {
        //проверка на заполненность поля
        if (empty($sign_up[$value])) {
            $errors[$value] = 'Это поле надо заполнить, падла!';
        }
        else {
            //проверка на email
            if ($value = 'email') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_EMAIL)) {
                    $errors[$value] = 'Введи нормальное мыло, кретин!';
                }
            }
            }
        }
    //валидация данных
    /**if (empty($errors)) {
        foreach ($users as $user) {
            if ($auth['email'] == $user['email']) {
                if (password_verify($auth['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                    break;
                }
                else {
                    $errors['password'] = 'Пароль неверный, падла!';
                    break;
                }
            }
            else {
                $errors['email'] = 'Сие мыло не зарегано. Падла!';
            }
        }
    }  **/
    if (count($errors)) {
        $main_content = templating("templates/sign-up.php", ['category' => $category, 'errors' => $errors, 'sign_up' => $sign_up] );
    }
    else {
        header("Location: /index.php");
        exit();
    }
}
else {
    $main_content = templating("templates/sign-up.php", ['category' => $category]);
}

$layout_content = templating("templates/layout.php", ["page_name" => "", "main_content" => $main_content, "category" => $category]);
var_dump($errors);
echo "<br>";
var_dump($sign_up);
print $layout_content;

