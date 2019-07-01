<?php

//функция шаблонизатор
function templating($template_route, $template_data) {      ///функция шаблонизации
        if (file_exists($template_route) == false){
            return 'ERROR!!!';
        }
        else {
            ob_start();
            extract($template_data);
            require_once $template_route;
            $template = ob_get_contents();
            ob_end_clean();
            return $template;
        }
    }

///функция форматирования отображения стоимости лота (чтобы было красиво)
function format_sum($format_sum)
    {
    $format_sum = ceil($format_sum);
    if ($format_sum > 1000) {
        $format_sum = number_format($format_sum, 0, ",", " ");
    }
    return $format_sum." &#8381";
};

///функция вывода часов и минут, оставшихся до окончания действия лота
function lot_timer () {
    $ts = time();
    $midnight = strtotime("tomorrow");
    $secs_to_midnight = $midnight - $ts;
    $hours = floor($secs_to_midnight/3600);
    $minutes = floor(($secs_to_midnight % 3600)/60);
    return "$hours:$minutes";
}

// уже готовая функция приведения введенных данных к безопасному виду
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
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


