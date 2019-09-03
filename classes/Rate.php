<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 03.09.19
 * Time: 5:15
 */

namespace classes;

use classes\DataBase;
use PDOException;
use PDO;

class Rate
{
    public static function addRate($lot_id, $add_rate)
    {
        try {
            $pdo = DataBase::databaseConnect();
            $pdo->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql_ins_rate = "INSERT INTO rate (date, bet, id_lot, id_user) VALUES (NOW(), ?, ?, ?)";
            $sql_upd_price = "UPDATE lot SET price = ? WHERE lot.id = ?";
            DataBase::udpateDatabase($sql_ins_rate, [$add_rate['cost'], $lot_id, $_SESSION["user"]["id"]]);
            DataBase::udpateDatabase($sql_upd_price, [$add_rate['cost'], $lot_id]);
            $pdo->commit();
            header("Location: lot.php?lot_id=" . $lot_id);
            exit();
        } catch (PDOException $ex) {
            $pdo->rollback();
            echo "Cоединение прервано!" . $ex->getMessage();
            exit();
        }
    }

    public static function checkAddRate($add_rate, $lot)
    {
        $add_rate = filter_input_array(INPUT_POST, $add_rate);
        $errors = [];
        if (!isset($_SESSION["user"])) {
            $errors["auth"] = "Зарегайся, падла";
        } else {
            if (empty($add_rate['cost'])) {
                $errors['cost'] = 'Это поле надо заполнить, падла!';
            } else if (!filter_var($add_rate['cost'], FILTER_VALIDATE_INT) || $add_rate['cost'] < $lot['price'] + $lot['step']) {
                $errors["cost"] = 'Cделай нормальную ставку, кретин!';
            }

        }
        return $errors;
    }

    public static function userRates()
    {
        $user_id = $_SESSION["user"]["id"];
        $sql = "SELECT rate.*, lot.name, lot.message, lot.img, lot.id_category, id_winner, users.contact, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state FROM rate
  JOIN lot ON lot.id = rate.id_lot
  JOIN users ON lot.id_author = users.id
  JOIN (SELECT MAX(rate.id) as last_rate FROM rate WHERE rate.id_user = $user_id GROUP BY rate.id_lot) as l ON rate.id = last_rate
ORDER BY rate.date DESC";
        $lot = DataBase::searchInDatabase($sql);
        return $lot;
    }

    public static function lotRates($lot_id)
    {
        $sql = "SELECT bet, DATE_FORMAT(rate.date, '%d.%m.%y в %H:%i') as 'date_add', users.name FROM rate, users WHERE rate.id_lot = ? AND rate.id_user = users.id ORDER BY rate.date DESC";
        $rates = DataBase::searchInDatabase($sql, [$lot_id]);
        return $rates;
    }

}
