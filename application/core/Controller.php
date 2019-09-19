<?php

namespace application\core;

use application\core\View;
use application\core\Model;
use application\lib\DataBase;

abstract class Controller
{
    public $route;
    public $view;
    public $model;
    public $category;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = new View($route);
        $this->model = $this->loadModel($route["controller"]);
        $pdo = new DataBase();
        $this->category = $pdo->getData("SELECT * FROM category ORDER BY id ASC ;");
    }

    public function loadModel($name) {
        $path = "application\models\\" . ucfirst($name);
        if (class_exists($path)) {
            return new $path;
        } else {
            echo "Moдель не найдена!!!";
        }
    }

}