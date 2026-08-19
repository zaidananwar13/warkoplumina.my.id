<?php

namespace App\Core;

/**
 * HTTP Request Wrapper
 *
 * Provides clean access to request data (GET, POST, FILES, SERVER).
 */
class Request
{
    private array $get;
    private array $post;
    private array $files;
    private array $server;

    public function __construct(
        ?array $get = null,
        ?array $post = null,
        ?array $files = null,
        ?array $server = null
    ) {
        $this->get = $get ?? $_GET;
        $this->post = $post ?? $_POST;
        $this->files = $files ?? $_FILES;
        $this->server = $server ?? $_SERVER;
    }

    /**
     * Get the HTTP method (GET, POST, etc.).
     */
    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get the request URI path.
     */
    public function uri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return '/' . trim(parse_url($uri, PHP_URL_PATH), '/');
    }

    /**
     * Get a query string parameter.
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Get a POST parameter.
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Get all POST data.
     */
    public function all(): array
    {
        return $this->post;
    }

    /**
     * Get an uploaded file.
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Check if request has a file upload.
     */
    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && !empty($this->files[$key]['name']);
    }

    /**
     * Check if request method is POST.
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * Get all query parameters.
     */
    public function queryAll(): array
    {
        return $this->get;
    }

    /**
     * Get a server variable.
     */
    public function server(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }
}
