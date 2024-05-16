<?php

namespace router;

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function addRoute($method, $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

public function dispatch($method, $path)
{
    if (isset($this->routes[$method][$path])) {
        $handler = $this->routes[$method][$path];
        if (is_callable($handler)) {
            // If the handler is a callable function, call it
            call_user_func($handler);
        } else {
            // If the handler is a controller method, call it
            list($controller, $method) = $handler;
            $controllerInstance = new $controller();
            $controllerInstance->$method();
        }
    } else {
        // Handle 404 Not Found
        http_response_code(404); // Set 404 HTTP response code
        echo "404 Not Found"; // Echo 404 message
    }
}
}


