<?php

namespace application\controllers;

use application\core\Controller;

class LotController extends Controller
{
    public function lotAction()
    {
        $lot_id = filter_var($this->route['id'], FILTER_VALIDATE_INT);
        if (!$lot_id) {
            return http_response_code(404);
        }
        $lot = $this->model->getlotById($lot_id);
        if (empty($lot)) {
            return http_response_code(404);
        }
        set_cookie($lot_id);
        $rates = $this->model->getLotRates($lot_id);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $this->model->checkAddRate($_POST, $lot);
            if (count($errors)) {
                $this->view->templating(["page_name" => $lot["name"], 'category' => $this->category, 'errors' => $errors, "lot" => $lot, "rates" => $rates]);
            } else {
                $this->model->addRate($lot_id, $_POST);
                header("Location: /lot/" . $lot_id);
                exit();
            }
        } else {
            $this->view->templating(["page_name" => $lot["name"], "lot" => $lot, "category" => $this->category, "rates" => $rates]);
        }
    }
}