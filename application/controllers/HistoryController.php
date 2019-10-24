<?php

namespace application\controllers;

use application\core\Controller;

class HistoryController extends Controller
{
    public function historyAction()
    {
        if (isset($_COOKIE['history'])) {
            $lot = $this->model->getLotViewHistory($_COOKIE['history']);
            if (empty($lot)) {
                $this->view->templating(["page_name" => "История просмотров", "message" => "История просмотров пуста", 'category' => $this->category]);
            } else {
                $lot = $this->model->checkLotStatus($lot);
                $this->view->templating(["page_name" => "История просмотров", "message" => "История просмотров", "lot" => $lot, 'category' => $this->category]);
            }
        } else {
            $this->view->templating(["page_name" => "История просмотров", "message" => "История просмотров пуста", 'category' => $this->category]);
        }
    }
}