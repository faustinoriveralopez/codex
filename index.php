<?php
// index.php

require_once 'config/config.php';

// Helper for XSS protection
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Simple Autoloader
spl_autoload_register(function ($class) {
    // Prefix mapping
    $prefixes = [
        'Core\\' => 'core/',
        'App\\' => 'app/'
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

// Start Session
session_start();

use Core\Router;

$router = new Router();

// Load Routes
require_once 'app/routes.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
