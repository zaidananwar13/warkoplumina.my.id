<?php

namespace App\Core;

/**
 * Simple Router
 *
 * Maps HTTP method + URI pattern to controller actions.
 * Supports named parameters via {param} syntax.
 */
class Router
{
    private array $routes = [];
    private string $prefix = '';

    /**
     * Set a route group prefix.
     */
    public function group(string $prefix, callable $callback): void
    {
        $previousPrefix = $this->prefix;
        $this->prefix = $previousPrefix . $prefix;
        $callback($this);
        $this->prefix = $previousPrefix;
    }

    /**
     * Register a GET route.
     */
    public function get(string $uri, array|string $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, array|string $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Add a route to the collection.
     */
    private function addRoute(string $method, string $uri, array|string $action): void
    {
        $uri = $this->prefix . $uri;
        $uri = '/' . trim($uri, '/');

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
        ];
    }

    /**
     * Resolve the current request to a route.
     *
     * @return array{action: array|string, params: array}|null
     */
    public function resolve(string $method, string $uri): ?array
    {
        $uri = '/' . trim(parse_url($uri, PHP_URL_PATH), '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->buildPattern($route['uri']);

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'action' => $route['action'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    /**
     * Convert URI pattern with {param} to regex.
     */
    private function buildPattern(string $uri): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $uri);
        return '#^' . $pattern . '$#';
    }

    /**
     * Get all registered routes (for debugging).
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
