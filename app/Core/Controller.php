<?php

namespace App\Core;

/**
 * Base Controller
 *
 * Provides shared functionality for all controllers.
 */
abstract class Controller
{
    /**
     * Render a view and output it.
     */
    protected function view(string $template, array $data = []): void
    {
        View::display($template, $data);
    }

    /**
     * Send a JSON response.
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        Response::json($data, $statusCode);
    }

    /**
     * Redirect to a URL.
     */
    protected function redirect(string $url): void
    {
        Response::redirect($url);
    }
}
