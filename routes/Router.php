<?php
class Router implements IRouter
{
    private array $routes = [];
    private array $middlewares = [];

    public function addRoute(IRoute $route, string $method, string $path, callable $callback, callable $middleware = null): void
    {
        if ($path === '/') $path = '';

        $route_path = $route->getBasePath() . $path;
        $route_path = preg_replace('/\/:[^\/]+/', '/([^/]+)', $route_path);

        if ($route->getMiddleware()) $this->middlewares[$route->getBasePath()] = $route->getMiddleware();

        if (isset($this->routes[$route_path])) {
            $this->routes[$route_path][$method] = ['callback' => $callback, 'middleware' => $middleware];
            return;
        }

        $this->routes[$route_path] = [$method => ['callback' => $callback, 'middleware' => $middleware]];
    }

    private function getQuery(): array
    {
        $query_string = $_SERVER['QUERY_STRING'] ?? '';
        $query_params = [];
        parse_str($query_string, $query_params);
        return $query_params;
    }

    private function getBody(): array
    {
        $json_data = file_get_contents('php://input');
        if (!$json_data) return [];
        return json_decode($json_data, true);
    }

    private function getRouteCallback(string $method, string $path): array
    {
        $matched_route = null;
        $route_params = [];

        foreach ($this->routes as $route_path => $methods) {
            if (preg_match('#^' . $route_path . '$#', $path, $matches)) {
                array_shift($matches);

                $route_params = $matches;
                $matched_route = $methods;
                break;
            }
        }

        if (!$matched_route) {
            http_response_code(404);
            echo json_encode(['error' => 'Rota não encontrada', 'path' => $path]);
            exit;
        }

        $action = $matched_route[$method] ?? null;

        if (!$action) {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido', 'method' => $method]);
            exit;
        }

        return [$route_params, $action['callback'], $action['middleware']];
    }

    public function executeMiddlewares(string $path, array $query, array $body): void
    {
        $headers = getallheaders();
        foreach ($this->middlewares as $route_path => $middleware) {
            if (!preg_match('#^' . $route_path . '(/|$)#', $path)) continue;

            $middleware->execute($headers, $query, $body);
        }
    }

    public function run(string $method, string $path): void
    {
        try {
            $this->executeMiddlewares($path, $this->getQuery(), $this->getBody());

            $query = $this->getQuery();

            list($route_params, $callback, $middleware) = $this->getRouteCallback($method, $path);

            $body = [];
            if ($method !== 'GET') $body = $this->getBody();

            if ($middleware) $middleware($query, $body, $route_params);

            $callback($query, $body, $route_params);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
