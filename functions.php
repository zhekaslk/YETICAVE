<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 05.12.18
 * Time: 2:25
 */

function templating($template_route, $template_data) {          ///функция шаблонизации
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

function format_sum($format_sum) {          ///функция форматирования отображения стоимости лота
    $format_sum = ceil($format_sum);
    if ($format_sum > 1000) {
        $format_sum = number_format($format_sum, 0, ",", " ");
    }
    return $format_sum." &#8381";
};

function lot_timer () {             ///функция вывода часов и минут, оставшихся до окончания децствия лота
    $ts = time();
    $midnight = strtotime("tomorrow");
    $secs_to_midnight = $midnight - $ts;
    $hours = floor($secs_to_midnight/3600);
    $minutes = floor(($secs_to_midnight % 3600)/60);
    return "$hours:$minutes";
}