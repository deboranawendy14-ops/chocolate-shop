<?php
class Router {
    private array $routes = [];

    public function get(string $path, callable $handler): void {
        $this->routes[] = ['method' => 'GET', 'path' => $path, 'handler' => $handler];
    }

    public function post(string $path, callable $handler): void {
        $this->routes[] = ['method' => 'POST', 'path' => $path, 'handler' => $handler];
    }

    public function put(string $path, callable $handler): void {
        $this->routes[] = ['method' => 'PUT', 'path' => $path, 'handler' => $handler];
    }

    public function delete(string $path, callable $handler): void {
        $this->routes[] = ['method' => 'DELETE', 'path' => $path, 'handler' => $handler];
    }

    public function dispatch(): void {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/chocolate-shop/backend/index.php', '', $uri);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $pattern = '@^' . preg_replace('/\{[^}]+\}/', '([^/]+)', $route['path']) . '$@';
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        Response::error('Rota não encontrada.', 404);
    }
}