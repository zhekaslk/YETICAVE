<?php

/**
 * пропущенные пункты:
 * 1) проверка даты завершения лота
 * 3) отображение времени до окончания торгов
 * 4) пагинация
 * остановился на search.php
 *
 *
 *
 *
 *
 *
 * **/
$sql_history_for_auth = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner = $user_id THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state 
FROM lot JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
$sql_history_for_anon = "SELECT lot.id, lot.name, price, img, create_date, lot.message, category.name as category, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff,
CASE WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state
FROM lot JOIN category ON lot.id_category = category.id WHERE lot.id IN ($lot_id)";
$sql_mylots = "SELECT rate.*, lot.name, lot.message, lot.img, lot.id_category, id_winner, users.contact, TIMESTAMPDIFF(SECOND, NOW(), lot.end_date) as timediff, 
CASE WHEN id_winner = id_user THEN '1'
WHEN id_winner IS NULL THEN '3'
  ELSE '2'
END state FROM rate
  JOIN lot ON lot.id = rate.id_lot
  JOIN users ON lot.id_author = users.id
  JOIN (SELECT MAX(rate.id) as last_rate FROM rate WHERE rate.id_user = 24 GROUP BY rate.id_lot) as l ON rate.id = last_rate
ORDER BY rate.date DESC";
