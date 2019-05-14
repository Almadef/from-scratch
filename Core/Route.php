<?php

namespace Application\Core;

use Application\Exceptions\Exception404 as Exception404;

/**
 * Class Route
 * @package Application\Core
 */
class Route
{

    /**
     *
     */
    public static function start()
    {
        // контроллер и действие по умолчанию
        $controller_name = 'site';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $routes[1]=explode('?', $routes[1])[0];

        // получаем имя контроллера
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }

        // получаем имя экшена
        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }

        // добавляем префиксы
        $controller_name = ucfirst($controller_name) . 'Controller';
        $action_name = 'action' . $action_name;

        // создаем контроллер
        $controller_name = '\Application\Controllers\\' . $controller_name;
        if (!class_exists($controller_name)) {
            throw new Exception404();
        }
        $controller = new $controller_name;
        $action = explode('?', $action_name)[0];

        if (!method_exists($controller, $action)) {
            throw new Exception404();
        }
        // вызываем действие контроллера
        $controller->$action();
    }
}
