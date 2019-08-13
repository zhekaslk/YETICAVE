<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 13.07.19
 * Time: 2:19
 */
require_once "init.php";
require_once "vendor/autoload.php";
require_once "functions.php";

$sql_search_lots = "SELECT users.name, users.email, lot.name as lotname, price, rate.id_user, rate.id_lot FROM users, lot, rate
   JOIN (SELECT MAX(rate.id) as last_rate FROM rate
  GROUP BY rate.id_lot) AS l ON rate.id = l.last_rate
WHERE lot.end_date <= NOW() AND lot.id_winner IS NULL AND rate.id_lot = lot.id AND rate.id_lot = lot.id AND users.id = rate.id_user";
$result = $pdo->query($sql_search_lots)->fetchAll(PDO::FETCH_ASSOC);
$sql_add_winner = "UPDATE lot SET id_winner = ? WHERE lot.id = ?";
foreach ($result as $item) {
    $stmt = $pdo->prepare($sql_add_winner);
    $stmt->execute($item["id_user"], $item["id_lot"]);
    $mail_content = templating("templates/email.php", ["mail" => $item]);
    $transport = new Swift_SmtpTransport("smtp.gmail.com", 587, "tls");
    $transport->setUsername("");
    $transport->setPassword("");
    $message = new Swift_Message("Ваша ставка победила");
    $message->setTo($item["email"]);
    $message->setBody($mail_content);
    $mailer = new Swift_Mailer($transport);
    //$mailer->send($message);
}
