<?php

namespace App\Core;

/**
 * Application Bootstrap
 *
 * Initializes the application, loads routes, and dispatches requests.
 */
class Application
{
    private Router $router;
    private Request $request;
    private static ?Application $instance = null;

    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
        static::$instance = $this;
    }

    /**
     * Get the application singleton.
     */
    public static function getInstance(): ?Application
    {
        return static::$instance;
    }

    /**
     * Get the router instance.
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Get the request instance.
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Boot the application.
     */
    public function boot(): void
    {
        // Start session
        Session::start();

        // Set view path
        View::setPath(__DIR__ . '/../Views');

        // Load environment file if exists
        $this->loadEnv();
    }

    /**
     * Load routes from the routes directory.
     */
    public function loadRoutes(): void
    {
        $router = $this->router;
        require __DIR__ . '/../../routes/web.php';
    }

    /**
     * Run the application and dispatch the request.
     */
    public function run(): void
    {
        $method = $this->request->method();
        $uri = $this->request->uri();

        $route = $this->router->resolve($method, $uri);

        if ($route === null) {
            Response::notFound('404 - Halaman tidak ditemukan');
            return;
        }

        $this->dispatch($route['action'], $route['params']);
    }

    /**
     * Dispatch the resolved route to its controller.
     */
    private function dispatch(array|string $action, array $params): void
    {
        if (is_array($action)) {
            [$controllerClass, $method] = $action;
            $controller = new $controllerClass();
            $controller->$method($this->request, ...array_values($params));
        } elseif (is_string($action) && is_callable($action)) {
            call_user_func($action, $this->request, ...array_values($params));
        }
    }

    /**
     * Load .env file into environment.
     */
    private function loadEnv(): void
    {
        $envFile = __DIR__ . '/../../.env';

        if (!file_exists($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (!getenv($key)) {
                putenv("{$key}={$value}");
            }
        }
    }
}
