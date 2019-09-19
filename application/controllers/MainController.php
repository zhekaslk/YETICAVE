<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $lot = $this->model->getActualLots();
        $this->model->getWinner();
        $this->view->templating(["page_name" => "Главная", "product" => $lot, "category" => $this->category]);
    }
}