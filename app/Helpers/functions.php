<?php

/**
 * Global Helper Functions
 *
 * Utility functions available throughout the application.
 */

if (!function_exists('rupiah')) {
    /**
     * Format a number as Indonesian Rupiah currency.
     */
    function rupiah(int|float $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('base_url')) {
    /**
     * Generate the base URL with optional path.
     */
    function base_url(string $path = ''): string
    {
        $base = rtrim(getenv('APP_BASE_URL') ?: 'http://localhost:8080/', '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * Generate URL for a static asset.
     */
    function asset(string $path): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    /**
     * Generate URL for an uploaded file.
     */
    function upload_url(string $path): string
    {
        return base_url('uploads/' . ltrim($path, '/'));
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML entities for safe output.
     */
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect helper.
     */
    function redirect(string $url): void
    {
        \App\Core\Response::redirect($url);
    }
}

if (!function_exists('config')) {
    /**
     * Get a configuration value.
     */
    function config(string $file): array
    {
        static $cache = [];

        if (!isset($cache[$file])) {
            $path = __DIR__ . '/../../config/' . $file . '.php';
            $cache[$file] = file_exists($path) ? require $path : [];
        }

        return $cache[$file];
    }
}
