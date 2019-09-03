<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 03.09.19
 * Time: 5:20
 */

namespace classes;

use Swift_Mailer;
use classes\DataBase;

class User
{
    public static function addUser($newUser)
    {
        $pass = password_hash($newUser["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, avatar, contact, reg_date) VALUES (?, ?, ?, ?, ?,CURRENT_DATE())";
        DataBase::udpateDatabase($sql,[$newUser["name"], $newUser["email"], $pass, $newUser["message"], $newUser["avatar"]]);
    }

    public static function checkLogin($auth)
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
            $stmt = DataBase::searchInDatabase($sql, [$auth["email"]]);
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

    public static function checkSignUp(array $sign_up, $add_file)
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
                        $stmt = DataBase::searchInDatabase($sql, [$sign_up["email"]]);
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
            if ($file_type == "image/jpeg" OR $file_type == "image/jpg" OR $file_type == "image/png") {
                $errors['avatar'] = 'Загрузите картинку!';
            } else {
                move_uploaded_file($tmp_name, 'img' . $tmp_name);
                $sign_up['avatar'] = 'img' . $tmp_name;
            }
        }
        return ["errors" => $errors, "sign_up" => $sign_up];
    }

    public static function getWinner()
    {
        $sql_search = "SELECT users.name, users.email, lot.name as lotname, price, rate.id_user, rate.id_lot FROM users, lot, rate
   JOIN (SELECT MAX(rate.id) as last_rate FROM rate
  GROUP BY rate.id_lot) AS l ON rate.id = l.last_rate
WHERE lot.end_date <= NOW() AND lot.id_winner IS NULL AND rate.id_lot = lot.id AND rate.id_lot = lot.id AND users.id = rate.id_user";
        $sql_add_winner = "UPDATE lot SET id_winner = ? WHERE lot.id = ?";
        $lot = DataBase::searchInDatabase($sql_search);
        foreach ($lot as $item) {
            DataBase::udpateDatabase($sql_add_winner, [$item["id_user"], $item["id_lot"]]);
            self::sendEmail($item);
        }
        return $lot;
    }

    public static function sendEmail($lot)
    {
        $mail_content = templating("templates/email.php", ["mail" => $lot]);
        $transport = new \Swift_SmtpTransport("smtp.mailtrap.io", 2525);
        $transport->setUsername("5058f498cf06ae");
        $transport->setPassword("84fb15dbc028e0");
        $message = new \Swift_Message("Ваша ставка победила");
        $message->setTo($lot["email"]);
        $message->setFrom("YetiCave@gmai.com");
        $message->setBody($mail_content);
        $mailer = new Swift_Mailer($transport);
        try {
            $mailer->send($message);
        } catch (\Swift_TransportException $e) {
            echo $e->getMessage();
        }
    }
}