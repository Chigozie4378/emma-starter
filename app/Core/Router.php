<?php

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($uri, $action, $middlewares = [])
    {
        $this->routes['GET'][$this->normalize($uri)] = [
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function post($uri, $action, $middlewares = [])
    {
        $this->routes['POST'][$this->normalize($uri)] = [
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch($uri, $method)
    {
        $uri = $this->normalize($uri);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            die('404 Not Found');
        }

        $route = $this->routes[$method][$uri];

        $this->runMiddlewares($route['middlewares']);

        $action = $route['action'];

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_string($action) && strpos($action, '@') !== false) {
            [$controllerName, $methodName] = explode('@', $action);

            $controllerFile = app_path('Controllers/' . $controllerName . '.php');

            if (!file_exists($controllerFile)) {
                die("Controller {$controllerName} not found.");
            }

            require_once $controllerFile;

            $controller = new $controllerName();

            if (!method_exists($controller, $methodName)) {
                die("Method {$methodName} not found in {$controllerName}.");
            }

            return $controller->$methodName();
        }

        die('Invalid route action.');
    }

    private function runMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middlewareName) {
            $file = app_path('Middleware/' . $middlewareName . '.php');

            if (!file_exists($file)) {
                die("Middleware {$middlewareName} not found.");
            }

            require_once $file;

            $middleware = new $middlewareName();
            $middleware->handle();
        }
    }

    private function normalize($uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        return $uri === '' ? '/' : $uri;
    }
}