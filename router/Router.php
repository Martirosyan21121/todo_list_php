<?php

namespace router;
//use controller\UserController;
//require_once 'controller/UserController.php';
class Router
{
    protected static $routes = [];

    public static function get($route, $controller)
    {
        self::$routes['GET'][$route] = $controller;
    }

    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $route = strtok($uri, '?');

        if (isset(self::$routes[$method][$route])) {
            $controllerAction = self::$routes[$method][$route];
            if (is_array($controllerAction) && count($controllerAction) === 2) {
                $controllerName = $controllerAction[0];
                $action = $controllerAction[1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();

                    if (method_exists($controller, $action)) {
                        $controller->$action();
                    } else {
                        http_response_code(404);
                        echo '404 Not Found';
                    }
                } else {
                    http_response_code(404);
                    echo '404 Not Found';
                }
            } else {
                http_response_code(500);
                echo 'Internal Server Error';
            }
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}