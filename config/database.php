<?php

/**
 * Database Configuration
 */

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'name' => getenv('DB_NAME') ?: 'sina6694_warkoplumina',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8',
];
