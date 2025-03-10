<?php
class Route {
    private static $routes = [];

    // Define a new route
    public static function add($method, $uri, $controller, $action) {
        self::$routes[] = [
            'method' => strtoupper($method),
            'uri' => trim($uri, '/'),
            'controller' => $controller,
            'action' => $action
        ];
    }

    // Match the requested URI and method with the defined routes
    public static function dispatch() {
        $requestedUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestedMethod = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            if ($route['uri'] === $requestedUri && $route['method'] === $requestedMethod) {
                $controllerName = "Controllers\\" . $route['controller'];
                $action = $route['action'];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $action)) {
                        call_user_func_array([$controller, $action], []);
                        return;
                    }
                }
            }
        }

        // If no route matches, show a 404 error
        http_response_code(404);
        echo "404 Not Found";
    }
}
?>
