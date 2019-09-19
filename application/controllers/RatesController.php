<?php

namespace application\controllers;

use application\core\Controller;

class RatesController extends Controller
{
    public function ratesAction()
    {
        if (isset($_SESSION["user"])) {
            $lot = $this->model->userRates();
            $lot = $this->model->checkLotStatus($lot);
        } else {
            header("Location: /login");
        }
        $this->view->templating(["page_name" => "Мои ставки", "lot" => $lot, 'category' => $this->category]);
    }

}