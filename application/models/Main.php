<?php

namespace application\models;

use application\core\Model;
use Swift_Mailer;
use Swift_SmtpTransport;

class Main extends Model
{
    /**
     * Получение списка актуальных на даный момент лотов
     *
     * @return $lot Список лотов
     */
    public function getActualLots()
    {
        $sql = "SELECT lot.id, lot.name, price, img, create_date, COUNT(rate.id), category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff FROM lot
LEFT JOIN rate ON rate.id_lot = lot.id
JOIN category ON lot.id_category = category.id  
WHERE end_date > NOW()
GROUP BY lot.id
ORDER BY create_date DESC";
        $lot = $this->pdo->getData($sql);
        return $lot;
    }

    /**
     * Определение победителей в аукционах
     *
     */
    public function getWinner()
    {
        $sql_search = "SELECT users.name, users.email, lot.name as lotname, price, rate.id_user, rate.id_lot FROM users, lot, rate
   JOIN (SELECT MAX(rate.id) as last_rate FROM rate
  GROUP BY rate.id_lot) AS l ON rate.id = l.last_rate
WHERE lot.end_date <= NOW() AND lot.id_winner IS NULL AND rate.id_lot = lot.id AND rate.id_lot = lot.id AND users.id = rate.id_user";
        $sql_add_winner = "UPDATE lot SET id_winner = ? WHERE lot.id = ?";
        $lot = $this->pdo->getData($sql_search);
        foreach ($lot as $item) {
            $this->pdo->udpateDatabase($sql_add_winner, [$item["id_user"], $item["id_lot"]]);
            self::sendEmail($item);
        }
    }

    /**
     * Отправка на email сообщения победителю аукциона
     *
     * @param $lot Лот, на который пользователь выиграл аукцион
     *
     */
    public static function sendEmail($lot)
    {
        $mail_content = templating("application/views/email/email.php", ["mail" => $lot]);
        $transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
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