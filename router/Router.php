<?php

namespace router;

class Router
{
    protected $routes = [];

    public function get($route, $controller)
    {
        $this->routes['GET'][$route] = $controller;
    }

    public function post($route, $controller)
    {
        $this->routes['POST'][$route] = $controller;
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $route = strtok($uri, '?');

        if (isset($this->routes[$method][$route])) {
            $controllerAction = $this->routes[$method][$route];
            list($controllerName, $action) = explode('@', $controllerAction);

            require_once __DIR__ . "/controller/$controllerName.php";
            $controller = new $controllerName();
            $controller->$action();
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}