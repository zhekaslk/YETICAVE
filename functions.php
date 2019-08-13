<?php
require_once "vendor/autoload.php";

/**
 * Функция шаблонизатор
 *
 * @param $template_route Шаблон
 * @param $template_data Данные для шаблона
 *
 * @return template Готовый контент
 */
function templating($template_route, $template_data)
{      ///функция шаблонизации
    if (file_exists($template_route) == false) {
        return 'Something is going wrong. Please try again latter';
    } else {
        ob_start();
        extract($template_data);
        require_once $template_route;
        $template = ob_get_contents();
        ob_end_clean();
        return $template;
    }
}

/**
 * Функция форматирования отображения стоимости лота (чтобы было по-красоте)
 *
 * @param $format_sum Сумма которую нужно сделать красивой, т.е. отделить пробелом каждый порядок и добавить знак рубля *
 *
 * @return format_sum Красивое число со знаком рубля
 */
function format_sum($format_sum)
{
    $format_sum = ceil($format_sum);
    if ($format_sum > 1000) {
        $format_sum = number_format($format_sum, 0, ",", " ");
    }
    return $format_sum . " &#8381";
}

/**
 * Функция вывода дней, часов и минут, оставшихся до окончания действия лота
 *
 * @param $seconds_to Количество секунд до окончания лота
 *
 * @return Дни, часы и минуты до окончания
 */
function lot_timer($seconds_to)
{
    $m = floor(($seconds_to % 3600) / 60);
    $h = floor(($seconds_to % 86400) / 3600);
    $d = floor(($seconds_to % 2592000) / 86400);
    return $d . "д " . $h . "ч " . $m . "мин";
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 * @deprecated используются фунции PDO
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

/**
 * Функция для проверки статуса лота (завершен, завершен и выигран пользователем или не завершен), добавляет стили для вывода информации
 *
 * @param $lot Массив с лотами
 *
 * @return lot Массив с лотами, к которому в соответствии с его статусом были добавлены сообщения и стили для отображения
 */

function check_lot_status($lot)
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

/**
 * Функция для записи в куки id посещенных страниц
 *
 * @param $lot_id ID cтраницы
 */
function set_cookie($lot_id)
{
    $counter_name = "history";
    $counter_value = [];
    $expire = strtotime("+30 days");
    $path = "/";
    if (isset($_COOKIE['history'])) {
        $counter_value = explode(",", $_COOKIE['history']);
        if (!in_array($lot_id, $counter_value))
            $counter_value [] = $lot_id;
    } else {
        $counter_value [] = $lot_id;
    }
    setcookie($counter_name, implode(",", $counter_value), $expire, $path);
}