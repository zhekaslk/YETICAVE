<?php

namespace application\models;

use application\core\Model;

class History extends Model
{
    /**
     * Функция поиска списка просмотренных лотов
     *
     * @param $lotIdFromCookie Список id лотов из кук
     *
     * @return $lot Список лотов
     */
    public function getLotViewHistory($lotIdFromCookie)
    {
        $lot_id = strip_tags(htmlspecialchars($lotIdFromCookie));
        if ($_SESSION) {
            $id_user = $_SESSION["user"]["id"];
            $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner = $id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
 FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
        } else {
            $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
 FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
        }
        $lot = $this->pdo->getData($sql);
        return $lot;
    }
}