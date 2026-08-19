<?php

namespace App\Core;

/**
 * Simple View/Template Engine
 *
 * Renders PHP template files with extracted variables.
 * Supports layouts via sections.
 */
class View
{
    private static string $viewPath = '';

    /**
     * Set the base view directory path.
     */
    public static function setPath(string $path): void
    {
        static::$viewPath = rtrim($path, '/');
    }

    /**
     * Render a view template.
     *
     * @param string $view  Dot-notation view name (e.g., 'home.index')
     * @param array  $data  Variables to pass to the view
     */
    public static function render(string $view, array $data = []): string
    {
        $path = static::$viewPath ?: __DIR__ . '/../../app/Views';
        $file = $path . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$view} ({$file})");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        return ob_get_clean();
    }

    /**
     * Render and output a view template.
     */
    public static function display(string $view, array $data = []): void
    {
        echo static::render($view, $data);
    }
}
