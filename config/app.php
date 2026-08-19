<?php

/**
 * Application Configuration
 *
 * Central configuration file for Warkop Lumina ordering system.
 */

return [
    'name' => 'Warkop Lumina Tebet',
    'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost:8080/',
    'debug' => (bool)(getenv('APP_DEBUG') ?: false),
];
