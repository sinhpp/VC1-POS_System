<?php
require_once(__DIR__ . '/../Controllers/FormController.php');
$router = new Router();
$router->get('/form', [new FormController(), 'form']); // Route to form

class Router 
{
    private $uri;
    private $method;
    private $routes = [];

    /**
     * Constructor to initialize the URI and request method.
     */
    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Registers a GET route.
     *
     * @param string $uri The URI of the route.
     * @param array $action The controller class and method to be executed.
     */
    public function get($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'GET',
            'action' => $action
        ];
    }

    /**
     * Registers a POST route.
     *
     * @param string $uri The URI of the route.
     * @param array $action The controller class and method to be executed.
     */
    public function post($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'POST',
            'action' => $action
        ];
    }

    /**
     * Registers a PUT route.
     *
     * @param string $uri The URI of the route.
     * @param array $action The controller class and method to be executed.
     */
    public function put($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'PUT',
            'action' => $action
        ];
    }

    /**
     * Registers a DELETE route.
     *
     * @param string $uri The URI of the route.
     * @param array $action The controller class and method to be executed.
     */
    public function delete($uri, $action)
    {
        $this->routes[$uri] = [
            'method' => 'DELETE',
            'action' => $action
        ];
    }

    /**
     * Routes the request to the appropriate controller and method.
     */
    public function route()
    {
        foreach ($this->routes as $uri => $route) {
            // Convert route pattern to a regex that matches numbers (for IDs)
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([0-9]+)', trim($uri, '/'));

            if (preg_match("#^$pattern$#", trim($this->uri, '/'), $matches)) {
                array_shift($matches); // Remove full match
                $controllerClass = $route['action'][0];
                $function = $route['action'][1];

                $controller = new $controllerClass();
                if (method_exists($controller, $function)) {
                    if (empty($matches)) {
                        $controller->$function();
                    } else {
                        call_user_func_array([$controller, $function], $matches);
                    }
                } else {
                    // Handle the case where method doesn't exist
                    // Either call a default method or show a 404 page
                    if (method_exists($controller, 'index')) {
                        $controller->index();
                    } else {
                        // Show 404 page or redirect
                        header("HTTP/1.0 404 Not Found");
                        echo "404 - Page not found";
                    }
                }
                exit;
            }
        }

        http_response_code(404);
        require_once 'views/errors/404.php';
    }

    
}
