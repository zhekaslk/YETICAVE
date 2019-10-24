<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 11.09.19
 * Time: 20:22
 */

namespace application\models;

use application\core\Model;

class Account extends Model
{
    /**
     * Функция добавления нового пользователя
     *
     * @param $newUser Данные нового пользователя
     * @return template Готовый контент
     */
    public function addUser($newUser)
    {
        $pass = password_hash($newUser["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, contact, avatar,  reg_date) VALUES (?, ?, ?, ?, ?,CURRENT_DATE())";
        $this->pdo->udpateDatabase($sql,[$newUser["name"], $newUser["email"], $pass, $newUser["message"], $newUser["avatar"]]);
    }

    /**
     * Функция проверки данных авторизации
     *
     * @param $auth Данные авторизации
     *
     * @return $errors и $auth Массив ошибок и данные авторизации
     */
    public function checkLogin($auth)
    {
        $auth = filter_input_array(INPUT_POST, $auth);
        $errors = [];
        //проверка на заполненность полей добавление ошибок в массив, если таковые имеются
        if (empty($auth['email'])) {
            $errors['email'] = 'Это поле надо заполнить, падла!';
        }
        if (empty($auth['password'])) {
            $errors['password'] = 'Это поле надо заполнить, падла!';
        }
        //валидация введенных данных
        if (empty($errors)) {
            $sql = "SELECT id, email, password, name, avatar FROM users WHERE email = ?";
            $stmt = $this->pdo->getData($sql, [$auth["email"]]);
            if (count($stmt)) {
                if (!password_verify($auth["password"], $stmt[0]["password"])) {
                    $errors['auth'] = 'Вы ввели неверный логин/пароль';
                } else {
                    $auth = $stmt[0];
                }
            } else {
                $errors['auth'] = 'Вы ввели неверный логин/пароль';
            }
        }
        return ["errors" => $errors, "auth" => $auth];
    }

    /**
     * Функция проверки данных регистрации
     *
     * @param $sign_up и $add_fileДанные регистрации и загруженный файл аватара
     *
     * @return $errors и $auth Массив ошибок и данные авторизации
     */
    public function checkSignUp(array $sign_up, $add_file)
    {
        $sign_up = filter_input_array(INPUT_POST, $sign_up);
        $errors = [];
        $required = ["email", "password", "message", "name"];
        $sign_up['avatar'] = "";
        //проверка на заполненность полей
        foreach ($required as $value) {
            //проверка на заполненность поля
            if (empty($sign_up[$value])) {
                $errors[$value] = 'Это поле надо заполнить, падла!';
            } else {
                //проверка на email
                if ($value == 'email') {
                    if (!filter_var($_POST[$value], FILTER_VALIDATE_EMAIL)) {
                        $errors[$value] = 'Введи нормальное мыло, кретин!';
                    } else {
                        $sql = "SELECT email FROM users WHERE email = ? ";
                        $stmt = $this->pdo->getData($sql, [$sign_up["email"]]);
                        if (count($stmt)) {
                            $errors[$value] = 'Такое мыло уже есть, чебурек!!';
                        }
                    }
                }
            }
        }
        if (!empty($add_file['name'])) {
            $tmp_name = $add_file['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if ($file_type == "image/jpeg" || $file_type == "image/jpg" || $file_type == "image/png") {
                move_uploaded_file($tmp_name, 'public/img' . $tmp_name);
                $sign_up['avatar'] = '/public/img' . $tmp_name;
            } else {
                $errors['avatar'] = 'Загрузите картинку!';
            }
        }
        return ["errors" => $errors, "sign_up" => $sign_up];
    }
}