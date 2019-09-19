<?php

namespace application\models;

use application\core\Model;

class Rates extends Model
{
    public function userRates()
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
        $lot = $this->pdo->getData($sql);
        return $lot;
    }

}