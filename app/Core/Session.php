<?php

namespace App\Core;

/**
 * Session Manager
 *
 * Wraps PHP session functions for testability and clean access.
 */
class Session
{
    /**
     * Start the session if not already active.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_httponly', '1');
            session_start();
        }
    }

    /**
     * Get a session value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a session value.
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a session key exists.
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session key.
     */
    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the entire session.
     */
    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Generate and store a CSRF token.
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /**
     * Validate a CSRF token.
     */
    public static function csrfCheck(string $token): bool
    {
        return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
    }
}
