<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller
{
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res = $this->model->checkLogin($_POST);
            $errors = $res["errors"];
            $auth = $res["auth"];
            if (count($errors)) {
                $this->view->templating(['category' => $this->category, 'errors' => $errors, 'auth' => $auth]);
            }
            else {
                $_SESSION['user'] = $auth;
                header("Location: /");
                exit();
            }
        } else {
            $this->view->templating(["page_name" => "Вход", "category" => $this->category]);
        }
    }

    public function registerAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res = $this->model->checkSignUp($_POST, $_FILES["avatar"]);
            $sign_up = $res["sign_up"];
            $errors = $res["errors"];
            if (count($errors)) {
                $this->view->templating(['category' => $this->category, 'errors' => $errors, 'sign_up' => $sign_up]);
            } else {
                $this->model->addUser($sign_up);
                header("Location: /login");
                //echo "Регистрация прошла успешно. Вы будуте перенаправлены на страницу входа, где сможете войти используя свой email и пароль";
                exit();
            }
        } else {
            $this->view->templating(['category' => $this->category, "page_name" => "Регистрация"]);
        }
    }

    public function logoutAction()
    {
        unset($_SESSION["user"]);
        header("Location: /index.php");
    }

}