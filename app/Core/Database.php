<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database Connection Singleton
 *
 * Manages PDO connection using configuration from config/database.php.
 */
class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    /**
     * Prevent direct instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Set custom configuration (useful for testing).
     */
    public static function setConfig(array $config): void
    {
        static::$config = $config;
        static::$instance = null;
    }

    /**
     * Get the PDO connection instance.
     */
    public static function getConnection(): PDO
    {
        if (static::$instance === null) {
            $config = static::$config ?: require __DIR__ . '/../../config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['name'],
                $config['charset'] ?? 'utf8'
            );

            try {
                static::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new PDOException(
                    'Database connection failed: ' . $e->getMessage(),
                    (int)$e->getCode()
                );
            }
        }

        return static::$instance;
    }

    /**
     * Set a custom PDO instance (for testing with mocks).
     */
    public static function setConnection(PDO $pdo): void
    {
        static::$instance = $pdo;
    }

    /**
     * Reset connection (useful in tests).
     */
    public static function reset(): void
    {
        static::$instance = null;
        static::$config = [];
    }
}
