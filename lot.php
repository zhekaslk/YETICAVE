<?php
//страница отображения лота
require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
session_start();

$lot_id = $_GET['lot_id'];
//в данном запросе есть косяк с временем до окончания лота
$sql_lot = "SELECT lot.*,  category.name as category, DATEDIFF(end_date, NOW()) as 'end' FROM lot
JOIN category ON lot.id_category = category.id
WHERE lot.id = '$lot_id'";
$result = mysqli_query($con, $sql_lot);
if ($result) {
    $lot = mysqli_fetch_assoc($result);
    if(empty($lot)) {
        return http_response_code(404);
    }
}
else {
    $error = mysqli_error($con);
}

//слок отображения истории торгов (все ставки к данному лоту)
$sql_rate_list = "SELECT bet, DATE_FORMAT(rate.date, '%d.%m.%y в %H:%i') as 'date_add', users.name FROM rate, users WHERE rate.id_lot = $lot_id AND rate.id_user = users.id ORDER BY rate.date DESC";
$result = mysqli_query($con, $sql_rate_list);
if ($result) {
    $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($con);
}

/////// запись в куки id посещенных страниц
$expire = strtotime("+30 days");
$counter_name = "history";
$counter_value = [];
$expire = strtotime("+30 days");
$path = "/";
if (isset($_COOKIE['history'])) {
    $counter_value = json_decode($_COOKIE['history']);
    if (!in_array($lot_id, $counter_value))
    $counter_value [] = $lot_id;
}
setcookie($counter_name, json_encode($counter_value), $expire, $path);



if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $add_rate = $_POST;
    $errors = [];
    //проверка на авторизацию, заполненность и правильность ввода ставки
    if (!isset($_SESSION["user"])) {
        $errors["auth"] = "Зарегайся, падла";
    }
    else {
        if (empty($add_rate['cost'])) {
            $errors['cost'] = 'Это поле надо заполнить, падла!';
        }
        else if (!filter_var($add_rate['cost'], FILTER_VALIDATE_INT) OR $add_rate['cost']<= $lot['price'] + $lot['step']) {
            $errors["cost"] = 'Cделай нормальную ставку, кретин!';
        }
        //валидация данных, добавление даннх в базу и обновление цены лота объединены в транзакцию
        if (empty($errors)) {
            mysqli_query($con, "START TRANSACTION");
            $sql = "INSERT INTO rate (date, bet, id_lot, id_user) VALUES (NOW(), ?, ?, ?)";
            $sql1 = "UPDATE lot SET price = ? WHERE lot.id = ?";
            $stmt = db_get_prepare_stmt($con, $sql, [$add_rate['cost'], $lot_id , $_SESSION["user"]["id"]]);
            $stmt1 = db_get_prepare_stmt($con, $sql1, [$add_rate['cost'], $lot_id ]);
            $result = mysqli_stmt_execute($stmt);
            $result1 = mysqli_stmt_execute($stmt1);
            if ($result AND $result1) {
                mysqli_query($con, "COMMIT");
                header("Location: lot.php?lot_id=" . $lot_id);
                exit();
            }
            else {
                mysqli_query($con, "ROLLBACK");
                //error;
            }
        }
    }
    if (count($errors)) {
        $main_content = templating("templates/lot.php", ['category' => $category, 'errors' => $errors, "lot" => $lot, "rates" => $rates]);
    }
}
else {

    $main_content = templating("templates/lot.php", ["lot" => $lot, "rates" => $rates]);
}
$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "main_content" => $main_content, "category" => $category]);
//var_dump($lot);
print $layout_content;