<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 22.05.19
 * Time: 2:39
 */

require_once "functions.php";
require_once "data.php";
require_once "userdata.php";

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
    }
    if (count($errors)) {
        $main_content = templating("templates/login.php", ['category' => $category, 'errors' => $errors, 'auth' => $auth] );
    }
    else {
            header("Location: /index.php");
            exit();
        }
}
else {
    $main_content = templating("templates/login.php", ['category' => $category]);
}
//var_dump($auth);
//var_dump($users);
//var_dump($errors);

$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;