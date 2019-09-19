<?php

namespace application\models;

use application\core\Model;

class Search extends Model
{
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