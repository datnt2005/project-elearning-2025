<?php

class Router {
    private $routes = [];
    private $middleware = [];

    public function addRoute($route, $callback, $middleware = []) {
        $this->routes[$route] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function addMiddleware($middleware) {
        $this->middleware[] = $middleware;
    }

    public function dispatch() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE

        foreach ($this->routes as $route => $routeData) {
            $pattern = "#^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route) . "$#";
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);

                // // Run global middleware
                foreach ($this->middleware as $middleware) {
                    if (!$middleware()) {
                        return;
                    }
                }

                foreach ($routeData['middleware'] as $middleware) {
                    if (!$middleware()) {
                        return;
                    }
                }

                // Call the route callback
                return call_user_func_array($routeData['callback'], $matches);
            }
        }

        echo "404 Not Found";
    }

    public function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
}
?>