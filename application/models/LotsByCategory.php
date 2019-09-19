<?php

namespace application\models;

use application\core\Model;

class LotsByCategory extends Model
{
    public function lotsByCatagory($category)
    {
        $sql = "SELECT lot.*,  category.name as cat_name, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE lot.id_category = (SELECT category.id FROM category WHERE category.name_eng = ?) && end_date > NOW() ORDER BY lot.create_date DESC";
        $lot = $this->pdo->getData($sql, [$category]);
        return $lot;
    }

    public function getRusCategoryName($category)
    {
        $sql = "SELECT category.name FROM category WHERE category.name_eng = ?";
        $rusCategoryName = $this->pdo->getRow($sql, [$category]);
        return $rusCategoryName;
    }

}