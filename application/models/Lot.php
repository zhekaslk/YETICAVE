<?php

namespace application\models;

use application\core\Model;
use PDOException;
use PDO;

class Lot extends Model
{
    /**
     * Функция получения лота по идентификатору
     *
     * @param $lot_id Идентификатор лота
     *
     * @return $lot Лот
     */
    public function getlotById($lot_id)
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

    /**
     * Функция добавления ставки
     *
     * @param $lot_id id лота
     * @param $add_rate размер ставки
     *
     */
    public function addRate($lot_id, $add_rate)
    {
        try {
            $this->pdo->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
            $this->pdo->beginTransaction();
            $sql_ins_rate = "INSERT INTO rate (date, bet, id_lot, id_user) VALUES (NOW(), ?, ?, ?)";
            $sql_upd_price = "UPDATE lot SET price = ? WHERE lot.id = ?";
            $this->pdo->udpateDatabase($sql_ins_rate, [$add_rate['cost'], $lot_id, $_SESSION["user"]["id"]]);
            $this->pdo->udpateDatabase($sql_upd_price, [$add_rate['cost'], $lot_id]);
            $this->pdo->commit();
        } catch (PDOException $ex) {
            $this->pdo->rollback();
            echo "Cоединение прервано!" . $ex->getMessage();
            exit();
        }
    }

    /**
     * Функция проверки новой ставки
     *
     * @param $add_rate Новая ставка
     * @param $lot Данные лота
     *
     * @return $errors Список ошибок
     */
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

    /**
     * Функция получения списка ставок для лота
     *
     * @param $lot_id Идентификатор лота
     *
     * @return $rates Список ставок
     */
    public function getLotRates($lot_id)
    {
        $sql = "SELECT bet, DATE_FORMAT(rate.date, '%d.%m.%y в %H:%i') as 'date_add', users.name FROM rate, users WHERE rate.id_lot = ? AND rate.id_user = users.id ORDER BY rate.date DESC";
        $rates = $this->pdo->getData($sql, [$lot_id]);
        return $rates;
    }
}