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
                if (!filter_var($_POST[$value], FILTER_VALIDATE_INT) OR $_POST[$value]<= 0) {
                    $errors[$value] = 'Введи нормальную цену, кретин!';
                }
            }
            if ($value = 'lot-step') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_INT) OR $_POST[$value]<= 0) {
                    $errors[$value] = 'Введи нормальный шаг, кретин!';
                }
            }
        }
    }
    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type == "image/jpeg" OR $file_type == "image/jpg" OR $file_type == "image/png") {
            move_uploaded_file($tmp_name, 'img' . $tmp_name);
            $add_lot['picture'] = 'img' .$tmp_name;
        }
        else {
            $errors['file'] = 'Загрузите картинку!';
        }
    }
    else {
        $errors['file'] = 'Вы не загрузили файл!';
    }
    if (count($errors)) {
        $main_content = templating("templates/add.php", ['add_lot' => $add_lot, 'category' => $category, 'errors' => $errors] );
    }
    else {
        $category_id = $add_lot["category"];
        $sql_category_id = "SELECT id FROM category WHERE name = '$category_id'";
        $result_category = mysqli_query($con, $sql_category_id);
        $category_id = mysqli_fetch_assoc($result_category);
        $id_cat = $category_id['id'];
        $name = mysqli_real_escape_string($con, $add_lot["lot-name"]);
        $message = mysqli_real_escape_string($con, $add_lot["message"]);
        $lot_price = mysqli_real_escape_string($con, $add_lot["lot-price"]);
        $lot_step = mysqli_real_escape_string($con, $add_lot["lot-step"]);
        $picture = $add_lot["picture"];
        $userid = $_SESSION['user']['id'];
        $end_date = $add_lot["lot-date"];
        $sql = "INSERT INTO lot (name, message, img, price, step, create_date, end_date, id_category, id_author) VALUES ('$name', '$message', '$picture', '$lot_price', '$lot_step', (), '$end_date', '$id_cat', '$userid')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?lot_id=".$lot_id);
            exit();
        }
    }

}
else {
    $main_content = templating("templates/add.php", ['category' => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Добавь", "main_content" => $main_content, "category" => $category]);
var_dump($_SESSION);
print $layout_content;