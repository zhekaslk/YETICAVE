<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 14.08.19
 * Time: 3:15
 */

namespace lot;

use PDO;
use PDOException;
use Swift_Mailer;

class Lot
{

    public static function checkAddLot(array $add_lot, $add_file)
    {
        $add_lot = filter_input_array(INPUT_POST, $add_lot);
        $errors = [];
        foreach ($add_lot as $key => $value) {
            //проверка на заполненность поля
            if (empty($value)) {
                $errors[$key] = 'Это поле надо заполнить, падла!';
            } else {
                //проверка корректности цены (чтобы была числом больше 0)
                if ($value == 'lot-price') {
                    if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
                        $errors[$key] = 'Введи нормальную цену, кретин!';
                    }
                }
                //проверка корректности шага ставки (чтобы был числом больше 0)
                if ($value == 'lot-step') {
                    if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
                        $errors[$key] = 'Введи нормальный шаг, кретин!';
                    }
                }
                //проверка корректности даты окончания лота (чтобы была больше текущей хотя бы на день)
                if ($value == 'lot-date') {
                    if (strtotime($value . " - 1 days") < strtotime(date('y-m-d'))) {
                        $errors[$key] = 'Введи нормальную дату, кретин!';
                    }
                }
            }
        }
        if (!empty($add_file['name'])) {
            $tmp_name = $add_file['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if ($file_type == "image/jpeg" OR $file_type == "image/jpg" OR $file_type == "image/png") {
                move_uploaded_file($tmp_name, 'img' . $tmp_name);
                $add_lot['picture'] = 'img' . $tmp_name;
            } else {
                $errors['file'] = 'Загрузите картинку!';
            }
        } else {
            $errors['file'] = 'Вы не загрузили файл!';

        }
        return ["errors" => $errors, "add_lot" => $add_lot];
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
                        $stmt = self::searchInDatabase($sql, [$sign_up["email"]]);
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

    public static function addLot($add_lot)
    {
        $pdo = self::databaseConnect();
        $sql = "INSERT INTO lot (name, message, img, price, step, create_date, end_date, id_category, id_author) VALUES (?, ?, ?, ?, ?, NOW(), ?, (SELECT id FROM category WHERE name = ?), ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$add_lot["lot-name"], $add_lot["message"], $add_lot["picture"], $add_lot["lot-price"], $add_lot["lot-step"], $add_lot["lot-date"], $add_lot["category"], $_SESSION['user']['id']]);
        header("Location: lot.php?lot_id=" . $pdo->lastInsertId());
        exit();
    }

    public static function addUser($newUser)
    {
        $pdo = self::databaseConnect();
        $pass = password_hash($newUser["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, avatar, contact, reg_date) VALUES (?, ?, ?, ?, ?,CURRENT_DATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newUser["name"], $newUser["email"], $pass, $newUser["message"], $newUser["avatar"]]);
    }

    public static function addRate($lot_id, $add_rate)
    {
        try {
            $pdo = self::databaseConnect();
            $pdo->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql_ins_rate = "INSERT INTO rate (date, bet, id_lot, id_user) VALUES (NOW(), ?, ?, ?)";
            $sql_upd_price = "UPDATE lot SET price = ? WHERE lot.id = ?";
            $stmt = $pdo->prepare($sql_ins_rate);
            $stmt->execute([$add_rate['cost'], $lot_id, $_SESSION["user"]["id"]]);
            $stmt = $pdo->prepare($sql_upd_price);
            $stmt->execute([$add_rate['cost'], $lot_id]);
            $pdo->commit();
            header("Location: lot.php?lot_id=" . $lot_id);
            exit();
        } catch (PDOException $ex) {
            $pdo->rollback();
            echo "Cоединение прервано!" . $ex->getMessage();
            exit();
        }
    }

    public static function checkAddRate($add_rate, $lot)
    {
        $add_rate = filter_input_array(INPUT_POST, $add_rate);
        $errors = [];
        if (!isset($_SESSION["user"])) {
            $errors["auth"] = "Зарегайся, падла";
        } else {
            if (empty($add_rate['cost'])) {
                $errors['cost'] = 'Это поле надо заполнить, падла!';
            } else if (!filter_var($add_rate['cost'], FILTER_VALIDATE_INT) || $add_rate['cost'] < $lot['price'] + $lot['step']) {
                $errors["cost"] = 'Cделай нормальную ставку, кретин!';
            }

        }
        return $errors;
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
            $stmt = self::searchInDatabase($sql, [$auth["email"]]);
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

    public static function checkLotStatus($lot)
    {
        foreach ($lot as &$item) {
            switch ($item["state"]) {
                case 1:
                    $item["rate_style"] = "rates__item--win";
                    $item["timer_style"] = "timer--win";
                    $item["timer_status"] = "Ставка выиграла";
                    break;
                case 2:
                    $item["rate_style"] = "rates__item--end";
                    $item["timer_style"] = "timer--end";
                    $item["timer_status"] = "Торги окончены";
                    break;
                case 3:
                    $item["rate_style"] = "";
                    $item["timer_style"] = "timer--finishing";
                    $item["timer_status"] = lot_timer($item["timediff"]);
                    break;
            }
        }
        return $lot;
    }

    public static function searchInDatabase($sql, array $arg = [])
    {
        $pdo = self::databaseConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arg);
        $result = $stmt->fetchAll();
        return $result;
    }

    public static function udpateDatabase($sql, array $arg = [])
    {
        $pdo = self::databaseConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arg);
        return $stmt;
    }

    public static function userRates()
    {
        $user_id = $_SESSION["user"]["id"];
        $sql = "SELECT rate.*, lot.name, lot.message, lot.img, lot.id_category, id_winner, users.contact, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state FROM rate
  JOIN lot ON lot.id = rate.id_lot
  JOIN users ON lot.id_author = users.id
  JOIN (SELECT MAX(rate.id) as last_rate FROM rate WHERE rate.id_user = $user_id GROUP BY rate.id_lot) as l ON rate.id = last_rate
ORDER BY rate.date DESC";
        $lot = self::searchInDatabase($sql);
        return $lot;
    }

    public static function actualLotList()
    {
        $sql = "SELECT lot.id, lot.name, price, img, create_date, COUNT(rate.id), category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff FROM lot
LEFT JOIN rate ON rate.id_lot = lot.id
JOIN category ON lot.id_category = category.id  
WHERE end_date > NOW()
GROUP BY lot.id
ORDER BY create_date DESC";
        $lot = self::searchInDatabase($sql);
        return $lot;
    }

    public static function databaseConnect()
    {
        $host = "localhost";
        $user = "phpmyadmin";
        $password = "seaways17";
        $db = "yeticave";
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $password, $opt);
        } catch (PDOException $ex) {
            echo "Cоединение прервано!" . $ex->getMessage();
        }
        return $pdo;
    }

    public static function lotRates($lot_id)
    {
        $sql = "SELECT bet, DATE_FORMAT(rate.date, '%d.%m.%y в %H:%i') as 'date_add', users.name FROM rate, users WHERE rate.id_lot = ? AND rate.id_user = users.id ORDER BY rate.date DESC";
        $rates = self::searchInDatabase($sql, [$lot_id]);
        return $rates;
    }

    public static function lotInfo($lot_id)
    {
        $sql = "SELECT lot.*,  category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff FROM lot
JOIN category ON lot.id_category = category.id
WHERE lot.id = ?";
        $lot = self::searchInDatabase($sql, [$lot_id]);
        if (empty($lot)) {
            return $lot;
        } else {
            $lot[0]["timediff"] = lot_timer($lot[0]["timediff"]);
            return $lot[0];
        }

    }

    public static function lotsByCatagory($category_id)
    {
        $sql = "SELECT lot.*,  category.name as cat_name, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE lot.id_category = ? && end_date > NOW() ORDER BY lot.create_date DESC";
        $lot = Lot::searchInDatabase($sql, [$category_id]);
        return $lot;
    }

    public static function getWinner()
    {
        $sql_search = "SELECT users.name, users.email, lot.name as lotname, price, rate.id_user, rate.id_lot FROM users, lot, rate
   JOIN (SELECT MAX(rate.id) as last_rate FROM rate
  GROUP BY rate.id_lot) AS l ON rate.id = l.last_rate
WHERE lot.end_date <= NOW() AND lot.id_winner IS NULL AND rate.id_lot = lot.id AND rate.id_lot = lot.id AND users.id = rate.id_user";
        $sql_add_winner = "UPDATE lot SET id_winner = ? WHERE lot.id = ?";
        $lot = self::searchInDatabase($sql_search);
        foreach ($lot as $item) {
            self::udpateDatabase($sql_add_winner, [$item["id_user"], $item["id_lot"]]);
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

    public static function lotViewHistory($lotIdFromCookie)
    {
        $lot_id = strip_tags(htmlspecialchars($lotIdFromCookie));
        if ($_SESSION) {
            $id_user = $_SESSION["user"]["id"];
            $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner = $id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
 FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
        } else {
            $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
 FROM lot
  JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
        }
        $lot = self::searchInDatabase($sql);

        return $lot;
    }

    public static function searchLot($search)
    {
        if ($search) {
            if ($_SESSION) {
                $id_user = $_SESSION["user"]["id"];
                $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = $id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
            } else {
                $sql = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot
  JOIN category ON lot.id_category = category.id
WHERE MATCH(lot.name, lot.message) AGAINST(?)
ORDER BY create_date DESC";
            }
            $lot = Lot::searchInDatabase($sql,[$search]);
            return $lot;
        }
    }

}