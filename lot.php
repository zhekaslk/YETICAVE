<?php
//страница отображения лота
require_once "vendor/autoload.php";
require_once "functions.php";
require_once "data.php";
session_start();

$lot_id = filter_var($_GET['lot_id'], FILTER_VALIDATE_INT);
if (!$lot_id) {
    return http_response_code(404);
}
$sql_lot = "SELECT lot.*,  category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff FROM lot
JOIN category ON lot.id_category = category.id
WHERE lot.id = ?";
$stmt = $pdo->prepare($sql_lot);
$stmt->execute([$lot_id]);
$lot = $stmt->fetch();
if (empty($lot)) {
    return http_response_code(404);
} else {
    $lot["timediff"] = lot_timer($lot["timediff"]);
}
//блок отображения истории торгов (все ставки к данному лоту)
$sql_rate_list = "SELECT bet, DATE_FORMAT(rate.date, '%d.%m.%y в %H:%i') as 'date_add', users.name FROM rate, users WHERE rate.id_lot = ? AND rate.id_user = users.id ORDER BY rate.date DESC";
$stmt = $pdo->prepare($sql_rate_list);
$stmt->execute([$lot_id]);
$rates = $stmt->fetchAll();

//форма добавления ставки
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $add_rate = $_POST;
    $errors = [];
    //проверка на авторизацию, заполненность и правильность ввода ставки
    if (!isset($_SESSION["user"])) {
        $errors["auth"] = "Зарегайся, падла";
    } else {
        if (empty($add_rate['cost'])) {
            $errors['cost'] = 'Это поле надо заполнить, падла!';
        } else if (!filter_var($add_rate['cost'], FILTER_VALIDATE_INT) || $add_rate['cost'] < $lot['price'] + $lot['step']) {
            $errors["cost"] = 'Cделай нормальную ставку, кретин!';
        }

    }
    if (count($errors)) {
        $main_content = templating("templates/lot.php", ['category' => $category, 'errors' => $errors, "lot" => $lot, "rates" => $rates]);
    } else {
        //валидация данных, добавление даннх в базу и обновление цены лота объединены в транзакцию
        if (empty($errors)) {
            try {
                $pdo->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql_ins_rate = "INSERT INTO rate (date, bet, id_lot, id_user) VALUES (NOW(), ?, ?, ?)";
                $sql_upd_price = "UPDATE lot SET price = ? WHERE lot.id = ?";
                $stmt = $pdo->prepare($sql_ins_rate);
                $stmt->execute([$add_rate['cost'], $lot_id, $_SESSION["user"]["id"]]);
                $stmt = $pdo->prepare($sql_upd_price);
                $stmt->execute([$add_rate['cost'], $lot_id]);
                $pdo->commit();
                header("Location: lot.php?lot_id=" . $lot_id);
                exit();
            } catch (PDOException $e) {
                $pdo->rollback();
                header("Location: lot.php?lot_id=" . $lot_id);
                exit();
            }
        }
    }
} else {
    $main_content = templating("templates/lot.php", ["lot" => $lot, "rates" => $rates]);
}
set_cookie($lot_id);
$layout_content = templating("templates/layout.php", ["page_name" => "Лоты", "main_content" => $main_content, "category" => $category]);
print $layout_content;