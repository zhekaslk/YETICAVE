<?php

require_once "functions.php";
require_once "data.php";
require_once "init.php";

//session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sign_up = $_POST;
    $errors = [];
    $required = ["email", "password", "message", "name"];
    $sign_up['avatar'] = "";
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
                else {
                    $email = mysqli_real_escape_string($con, $sign_up["email"]);
                    $sql = "SELECT email FROM users WHERE email = '$email'";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) != 0) {
                        $errors[$value] = 'Такое мыло уже есть, чебурек!!';
                    }
                }
            }
            }
        }

    if (!empty($_FILES['avatar']['name'])) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpeg") {
            $errors['avatar'] = 'Загрузите картинку!';
        }
        else {
            move_uploaded_file($tmp_name, 'img' . $tmp_name);
            $sign_up['avatar'] = 'img' .$tmp_name;
        }
    }
    if (count($errors)) {
        $main_content = templating("templates/sign-up.php", ['category' => $category, 'errors' => $errors, 'sign_up' => $sign_up] );
    }
    else {
        $name = mysqli_real_escape_string($con, $sign_up["name"]);
        $email = mysqli_real_escape_string($con, $sign_up["email"]);
        $pass = password_hash($sign_up["password"], PASSWORD_DEFAULT);
        $contact = mysqli_real_escape_string($con, $sign_up["message"]);
        $avatar = $sign_up["avatar"];
        $sql = "INSERT INTO users (name, email, password, avatar, contact, reg_date) VALUES ('$name', '$email', '$pass', '$avatar', '$contact', CURRENT_DATE())";
        $result = mysqli_query($con, $sql);
        if ($result) {
            header( "Location: /login.php");
            //echo "Регистрация прошла успешно. Вы будуте перенаправлены на страницу входа, где сможете войти используя свой email и пароль";
            exit();
        }
        else {
            $errors["base"] = mysqli_connect_error();
            $main_content = templating("templates/error.php", ['errors' => $errors]);
        }
    }
}
else {
    $main_content = templating("templates/sign-up.php", ['category' => $category]);
}
var_dump($category);
$layout_content = templating("templates/layout.php", ["page_name" => "", "main_content" => $main_content, "category" => $category]);
print $layout_content;

