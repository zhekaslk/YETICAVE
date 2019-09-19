<?php

namespace application\core;

use application\lib\DataBase;
use PDOException;


abstract class Model
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new DataBase();
    }

    public static function checkLotStatus($lot)
    {
        foreach ($lot as &$item) {
            switch ($item["state"]) {
                case 1:
                    $item["rate_style"] = "rates__item--win";
                    $item["timer_style"] = "timer--win";
                    $item["timer_status"] = "Ставка выиграла";
                    break;
                case 2:
                    $item["rate_style"] = "rates__item--end";
                    $item["timer_style"] = "timer--end";
                    $item["timer_status"] = "Торги окончены";
                    break;
                case 3:
                    $item["rate_style"] = "";
                    $item["timer_style"] = "timer--finishing";
                    $item["timer_status"] = lot_timer($item["timediff"]);
                    break;
            }
        }
        return $lot;
    }

}