<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 22.05.19
 * Time: 2:39
 */

require_once "functions.php";
require_once "data.php";
require_once "init.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $auth = $_POST;
    $errors = [];
    //проверка на заполненность полей
    if (empty($auth['email'])) {
        $errors['email'] = 'Это поле надо заполнить, падла!';
    }
    if (empty($auth['password'])) {
        $errors['password'] = 'Это поле надо заполнить, падла!';
    }
    //валидация данных
    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $auth["email"]);
        $sql = "SELECT id, email, password, name, avatar FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) != 0) {
            $result_arr = mysqli_fetch_assoc($result);
            if (!password_verify($auth["password"], $result_arr["password"])) {
                $errors['auth'] = 'Вы ввели неверный логин/пароль';
            }
        }
        else {
            $errors['auth'] = 'Вы ввели неверный логин/пароль';
        }
    }
    if (count($errors)) {
        $main_content = templating("templates/login.php", ['category' => $category, 'errors' => $errors, 'auth' => $auth]);
    }
    else {
            $_SESSION['user'] = $result_arr;
            header("Location: /index.php");
            exit();
        }
}
else {
    $main_content = templating("templates/login.php", ['category' => $category]);
}

$layout_content = templating("templates/layout.php", ["page_name" => "Главная", "main_content" => $main_content, "category" => $category]);
print $layout_content;