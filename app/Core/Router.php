<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function add($method, $uri, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => trim($uri, '/'),
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method) {
        $urlPath = parse_url($uri, PHP_URL_PATH);
        
        // Robust way to find base path (subdirectory)
        $scriptUrl = $_SERVER['SCRIPT_NAME'];
        $basePath = implode('/', array_intersect(explode('/', $urlPath), explode('/', $scriptUrl)));
        
        if ($basePath !== '' && strpos($urlPath, $basePath) === 0) {
            $urlPath = substr($urlPath, strlen($basePath));
        }

        $uri = trim($urlPath, '/');
        
        foreach ($this->routes as $route) {
            $pattern = "@^" . $route['uri'] . "$@";
            
            // Check direct match first
            if ($route['uri'] == $uri && $route['method'] == $method) {
                return $this->executeController($route);
            }
            
            // Check Regex
            if (preg_match($pattern, $uri, $matches) && $route['method'] == $method) {
                array_shift($matches); // remove full match
                return $this->executeController($route, $matches);
            }
        }

        // 404
        http_response_code(404);
        require_once '../app/Views/layouts/header.php';
        echo '<div class="max-w-7xl mx-auto px-4 py-20 text-center">';
        echo '<h1 class="text-6xl font-bold text-gray-200 mb-4">404</h1>';
        echo '<p class="text-xl text-gray-600 mb-8">Page not found.</p>';
        echo '<a href="/" class="text-blue-600 hover:text-blue-800">Go Home</a>';
        echo '</div>';
        require_once '../app/Views/layouts/footer.php';
    }

    private function executeController($route, $params = []) {
        $controllerClass = "App\\Controllers\\" . $route['controller'];
        $action = $route['action'];

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $action)) {
                return call_user_func_array([$controller, $action], $params);
            }
        }
        return false;
    }
}
