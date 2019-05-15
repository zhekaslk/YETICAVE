<?php

/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 10.12.18
 * Time: 9:48
 */


require_once "functions.php";
require_once "data.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $add_lot = $_POST;
    //var_dump($add_lot);
    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    foreach ($required as $value) {
        //проверка на заполненность
        if (empty($_POST[$value])) {
            $errors[$value] = 'Это поле надо заполнить, падла!';
        }
        else {
            //проверка на число
            if ($value = 'lot-rate') {
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
    var_dump($_FILES);
    if (isset($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $path = $_FILES['lot-img']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите картинку в формате GIF';
        }
        else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $add_lot['path'] = $path;
        }
    }
    else {
        $errors['file'] = 'Вы не загрузили файл';
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






/**require_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $add_lot = $_POST;

    $required = ['lot-name', 'category', 'message', 'lot-rate'];
    $dict = ['title' => 'Название', 'description' => 'Описание', 'file' => 'Гифка'];
    $errors = [];
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    if (isset($_FILES['gif_img']['name'])) {
        $tmp_name = $_FILES['gif_img']['tmp_name'];
        $path = $_FILES['gif_img']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/gif") {
            $errors['file'] = 'Загрузите картинку в формате GIF';
        }
        else {
            move_uploaded_file($tmp_name, 'uploads/' . $path);
            $gif['path'] = $path;
        }
    }
    else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    if (count($errors)) {
        $page_content = include_template('add.php', ['gif' => $gif, 'errors' => $errors, 'dict' => $dict]);
    }
    else {
        $page_content = include_template('view.php', ['gif' => $gif]);
    }
}
else {
    $page_content = include_template('add.php', []);
}

$layout_content = include_template('layout.php', [
    'content'    => $page_content,
    'categories' => [],
    'title'      => 'GifTube - Добавление гифки'
]);

print($layout_content); **/


