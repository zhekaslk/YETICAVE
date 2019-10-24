<?php

namespace application\core;


class View
{
    public $path;
    public $route;
    public $layout = "default";

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route["controller"] . "/" . $route["action"];
    }

    /**
     * Функция шаблонизатор
     *
     * @param $template_data Массив данных для передачи в шаблон
     *
     */
    public function templating($template_data = [])
    {
        ob_start();
        extract($template_data);
        require_once "application/views/" . $this->path . ".php";
        $main_content = ob_get_clean();
        require_once "application/views/layouts/" . $this->layout . ".php";
    }
}