<?php
//ДОБАВЛЕНИЕ ЛОТА

require_once "functions.php";
require_once "data.php";
session_start();

if (empty($_SESSION["user"])) {
    return http_response_code(403);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $add_lot = $_POST;
    $required = ['lot-name', 'category', 'message', 'lot-price', 'lot-step', 'lot-date'];
    $errors = [];
    foreach ($required as $value) {
        //проверка на заполненность поля
        if (empty($_POST[$value])) {
            $errors[$value] = 'Это поле надо заполнить, падла!';
        }
        else {
            //проверка на число
            if ($value = 'lot-price') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_INT)) {
                    $errors[$value] = 'Введи нормальную цену, кретин!';
                }
            }
            if ($value = 'lot-step') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_INT)) {
                    $errors[$value] = 'Введи число, кретин!';
                }
            }
        }
    }
    //var_dump($_FILES);
    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $path = $_FILES['lot-img']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите картинку!';
        }
        else {
            move_uploaded_file($path, 'img/' . $path);
            $add_lot['picture'] = $path;
        }
    }
    else {
        $errors['file'] = 'Вы не загрузили файл!';
    }
    if (count($errors)) {
        $main_content = templating("templates/add.php", ['add_lot' => $add_lot, 'category' => $category, 'errors' => $errors] );
    }
    else {
        $main_content = templating("templates/lot.php", ["lot" => $add_lot]);
    }

}
else {
    $main_content = templating("templates/add.php", ['category' => $category]);
}

$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "is_auth" => $is_auth, "user_avatar" => $user_avatar, "user_name" => $user_name, "main_content" => $main_content, "category" => $category]);
print $layout_content;