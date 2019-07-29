<?php
//СТРАНИЦА ДОБАВЛЕНИЯ ЛОТА

require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
session_start();

//проверка чтобы пользователь был авторизован
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
            //проверка корректности цены (чтобы была числом больше 0)
            if ($value = 'lot-price') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_INT) OR $_POST[$value]<= 0) {
                    $errors[$value] = 'Введи нормальную цену, кретин!';
                }
            }
            //проверка корректности шага ставки (чтобы был числом больше 0)
            if ($value = 'lot-step') {
                if (!filter_var($_POST[$value], FILTER_VALIDATE_INT) OR $_POST[$value]<= 0) {
                    $errors[$value] = 'Введи нормальный шаг, кретин!';
                }
            }
            //проверка корректности даты окончания лота (чтобы была больше текущей хотя бы на день)
            if ($value = 'lot-date') {
                if (strtotime($_POST[$value]." - 1 days") < strtotime(date('y-m-d'))) {
                    $errors[$value] = 'Введи нормальную дату, кретин!';
                }
            }
        }
    }
    //проверка загрузки файла изображения лота, его соответствие нужному расширению
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
    //если есть ошибки то отображаем их
    if (count($errors)) {
        $main_content = templating("templates/add.php", ['add_lot' => $add_lot, 'category' => $category, 'errors' => $errors] );
    }
    else {
        //костыль для получения id категории которую выбрал юзер
        $id_cat = $add_lot["category"];
        $sql_category_id = "SELECT id FROM category WHERE name = '$id_cat'";
        $id_cat = $pdo->query($sql_category_id)->fetch();
        // собственно добавление в базу нового лота
        $sql = "INSERT INTO lot (name, message, img, price, step, create_date, end_date, id_category, id_author) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$add_lot["lot-name"], $add_lot["message"] , $add_lot["picture"], $add_lot["lot-price"], $add_lot["lot-step"], $add_lot["lot-date"], $id_cat, $_SESSION['user']['id']]);
        $lot_id = $pdo->lastInsertId();
        header("Location: lot.php?lot_id=".$lot_id);
        exit();
    }
}
else {
    $main_content = templating("templates/add.php", ['category' => $category]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Добавь", "main_content" => $main_content, "category" => $category]);
//var_dump($add_lot);
print $layout_content;