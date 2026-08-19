<?php

/**
 * Front Controller
 *
 * Single entry point for all HTTP requests.
 * Boots the application, loads routes, and dispatches the request.
 */

// Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load global helper functions
require_once __DIR__ . '/../app/Helpers/functions.php';

// Boot application
$app = new \App\Core\Application();
$app->boot();

// Load routes
$app->loadRoutes();

// Dispatch the request
$app->run();
