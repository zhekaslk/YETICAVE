<?php

namespace application\controllers;

use application\core\Controller;

class LotsByCategoryController extends Controller
{
    public function lotsByCategoryAction()
    {
        $category = $this->route["category"];
        $lot = $this->model->lotsByCatagory($category);
        $category = $this->model->getRusCategoryName($category);
        $this->view->templating(["page_name" => $category, "lot" => $lot, "category" => $this->category, "current_cat" => $category]);
    }
}