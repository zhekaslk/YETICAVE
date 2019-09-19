<?php

namespace application\controllers;

use application\core\Controller;

class SearchController extends Controller
{
    public function searchAction()
    {
        if ($_GET["query"]) {
            $search = $this->route["search"];
            $lot = $this->model->searchLot($search);
            if (empty($lot)) {
                $this->view->templating(["page_name" => "Поиск", "category" => $this->category, "message" => "По вашему запросу ничего не найдено"]);
            } else {
                $lot = $this->model->checkLotStatus($lot);
                $this->view->templating(["page_name" => "Поиск", "lot" => $lot, "category" => $this->category, "search" => $search, "message" => "Результаты поиска по запросу "]);
            }
        } else {
            $this->view->templating(["page_name" => "Поиск", "category" => $this->category, "message" => "Вы ввели пустой поисковой запрос!"]);
        }
    }
}