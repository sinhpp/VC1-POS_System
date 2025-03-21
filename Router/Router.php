<?php
require_once(__DIR__ . '/../Controllers/FormController.php');

$router = new Router();
$router->get('/form', [FormController::class, 'form']); // Use class name instead of instance

class Router
{
    private $uri;
    private $method;
    private $routes = [];

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    private function addRoute($method, $uri, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => trim($uri, '/'),
            'action' => $action
        ];
    }

    public function get($uri, $action) { $this->addRoute('GET', $uri, $action); }
    public function post($uri, $action) { $this->addRoute('POST', $uri, $action); }
    public function put($uri, $action) { $this->addRoute('PUT', $uri, $action); }
    public function delete($uri, $action) { $this->addRoute('DELETE', $uri, $action); }

    public function route()
    {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $route['uri']);

            if ($this->method === $route['method'] && preg_match("#^$pattern$#", trim($this->uri, '/'), $matches)) {
                array_shift($matches); // Remove full match
                $controllerClass = $route['action'][0];
                $function = $route['action'][1];

                if (class_exists($controllerClass) && method_exists($controllerClass, $function)) {
                    $controller = new $controllerClass();
                    call_user_func_array([$controller, $function], $matches);
                    exit;
                }
            }
        }

        http_response_code(404);
        require_once 'views/errors/404.php';
    }
}
