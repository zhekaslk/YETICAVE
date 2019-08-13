<?php
//страница регистрации
require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
require_once "init.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sign_up = $_POST;
    $errors = [];
    $required = ["email", "password", "message", "name"];
    $sign_up['avatar'] = "";
    //проверка на заполненность полей
    foreach ($required as $value) {
        //проверка на заполненность поля
        if (empty($sign_up[$value])) {
            $errors[$value] = 'Это поле надо заполнить, падла!';
        } else {
            //проверка на email
            if ($value == 'email') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_EMAIL)) {
                    $errors[$value] = 'Введи нормальное мыло, кретин!';
                } else {
                    $sql = "SELECT email FROM users WHERE email = ? ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$sign_up["email"]]);
                    if ($stmt->rowCount() != 0) {
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
        } else {
            move_uploaded_file($tmp_name, 'img' . $tmp_name);
            $sign_up['avatar'] = 'img' . $tmp_name;
        }
    }
    if (count($errors)) {
        $main_content = templating("templates/sign-up.php", ['category' => $category, 'errors' => $errors, 'sign_up' => $sign_up]);
    } else {
        $pass = password_hash($sign_up["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, avatar, contact, reg_date) VALUES (?, ?, ?, ?, ?,CURRENT_DATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sign_up["name"], $sign_up["email"], $pass, $sign_up["message"], $sign_up["avatar"]]);
        header("Location: /login.php");
        //echo "Регистрация прошла успешно. Вы будуте перенаправлены на страницу входа, где сможете войти используя свой email и пароль";
        exit();
    }
} else {
    $main_content = templating("templates/sign-up.php", ['category' => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Регистрация", "main_content" => $main_content, "category" => $category]);
print $layout_content;

