<?php

/**
 * PHPUnit Bootstrap
 *
 * Loads the Composer autoloader, helper functions, and sets up the test environment.
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/functions.php';

// Set test environment variables
putenv('APP_DEBUG=true');
putenv('APP_BASE_URL=http://localhost:8080/');
putenv('DB_HOST=localhost');
putenv('DB_NAME=warkoplumina_test');
putenv('DB_USER=root');
putenv('DB_PASS=');
