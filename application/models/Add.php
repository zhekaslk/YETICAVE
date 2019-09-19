<?php

namespace application\models;

use application\core\Model;

class Add extends Model
{
    public function checkAddLot(array $add_lot, $add_file)
    {
        $add_lot = filter_input_array(INPUT_POST, $add_lot);
        $errors = [];
        foreach ($add_lot as $key => $value) {
            //проверка на заполненность поля
            if (empty($value)) {
                $errors[$key] = 'Это поле надо заполнить, падла!';
            } else {
                //проверка корректности цены (чтобы была числом больше 0)
                if ($value == 'lot-price') {
                    if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
                        $errors[$key] = 'Введи нормальную цену, кретин!';
                    }
                }
                //проверка корректности шага ставки (чтобы был числом больше 0)
                if ($value == 'lot-step') {
                    if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
                        $errors[$key] = 'Введи нормальный шаг, кретин!';
                    }
                }
                //проверка корректности даты окончания лота (чтобы была больше текущей хотя бы на день)
                if ($value == 'lot-date') {
                    if (strtotime($value . " - 1 days") < strtotime(date('y-m-d'))) {
                        $errors[$key] = 'Введи нормальную дату, кретин!';
                    }
                }
            }
        }
        if (!empty($add_file['name'])) {
            $tmp_name = $add_file['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if ($file_type == "image/jpeg" OR $file_type == "image/jpg" OR $file_type == "image/png") {
                move_uploaded_file($tmp_name, 'public/img' . $tmp_name);
                $add_lot['picture'] = '/public/img' . $tmp_name;
            } else {
                $errors['file'] = 'Загрузите картинку!';
            }
        } else {
            $errors['file'] = 'Вы не загрузили файл!';

        }
        return ["errors" => $errors, "add_lot" => $add_lot];
    }

    public function addLot($add_lot)
    {
        $sql = "INSERT INTO lot (name, message, img, price, step, create_date, end_date, id_category, id_author) VALUES (?, ?, ?, ?, ?, NOW(), ?, (SELECT id FROM category WHERE name = ?), ?)";
        $this->pdo->udpateDatabase($sql, [$add_lot["lot-name"], $add_lot["message"], $add_lot["picture"], $add_lot["lot-price"], $add_lot["lot-step"], $add_lot["lot-date"], $add_lot["category"], $_SESSION['user']['id']]);
        header("Location: lot/" . $this->pdo->getLastInsertId());
        exit();
    }

    public function lotInfo($lot_id)
    {
        $sql = "SELECT lot.*,  category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff FROM lot
JOIN category ON lot.id_category = category.id
WHERE lot.id = ?";
        $lot = $this->pdo->getData($sql, [$lot_id]);
        if (empty($lot)) {
            return $lot;
        } else {
            $lot[0]["timediff"] = lot_timer($lot[0]["timediff"]);
            return $lot[0];
        }

    }

    public function lotsByCatagory($category_id)
    {
        $sql = "SELECT lot.*,  category.name as cat_name, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE lot.id_category = ? && end_date > NOW() ORDER BY lot.create_date DESC";
        $lot = $this->pdo->getData($sql, [$category_id]);
        return $lot;
    }

    public function searchLot($search)
    {
        if ($search) {
            if ($_SESSION) {
                $id_user = $_SESSION["user"]["id"];
                $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = $id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
            } else {
                $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
            }
            $lot = $this->pdo->getData($sql,[$search]);
            return $lot;
        }
    }

}