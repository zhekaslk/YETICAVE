<?php

namespace application\controllers;

use application\core\Controller;

class AddController extends Controller
{
    public function addAction()
    {
        if (empty($_SESSION["user"])) {
            return http_response_code(403);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res = $this->model->checkAddLot($_POST, $_FILES["lot-img"]);
            $add_lot = $res["add_lot"];
            $errors = $res["errors"];
            if (count($errors)) {
                $this->view->templating(["page_name" => "Добавить лот", 'add_lot' => $add_lot, 'category' => $this->category, 'errors' => $errors]);
            } else {
                $this->model->addLot($add_lot);
            }
        } else {
            $this->view->templating(["page_name" => "Добавить лот", 'category' => $this->category]);
        }
    }
}