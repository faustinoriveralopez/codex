<?php
namespace Core;

class Router {
    protected $routes = [];

    public function add($method, $path, $controller, $action) {
        // Ensure path starts with /
        if ($path !== '/' && substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($path, $controller, $action) {
        $this->add('GET', $path, $controller, $action);
    }

    public function post($path, $controller, $action) {
        $this->add('POST', $path, $controller, $action);
    }

    public function dispatch($uri, $method) {
        $path = parse_url($uri, PHP_URL_PATH);

        // Normalize path
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            // Exact match
            if ($route['method'] == $method && $route['path'] == $path) {
                $controllerClass = "App\\Controllers\\" . $route['controller'];
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    $action = $route['action'];
                    if (method_exists($controller, $action)) {
                        $controller->$action();
                        return;
                    } else {
                        echo "Error: Method $action not found in controller $controllerClass";
                        return;
                    }
                } else {
                    echo "Error: Controller class $controllerClass not found";
                    return;
                }
            }
        }

        // 404
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found: " . htmlspecialchars($path);
    }
}
