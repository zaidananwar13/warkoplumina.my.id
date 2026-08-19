<?php

namespace App\Core;

/**
 * HTTP Response Helper
 *
 * Provides methods for sending responses (HTML, JSON, redirects).
 */
class Response
{
    /**
     * Send a JSON response.
     */
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirect to a URL.
     */
    public static function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Send a 404 Not Found response.
     */
    public static function notFound(string $message = 'Page not found'): void
    {
        http_response_code(404);
        echo $message;
        exit;
    }

    /**
     * Send an error response.
     */
    public static function error(string $message, int $statusCode = 500): void
    {
        http_response_code($statusCode);
        echo $message;
        exit;
    }

    /**
     * Set HTTP status code.
     */
    public static function status(int $code): void
    {
        http_response_code($code);
    }
}
