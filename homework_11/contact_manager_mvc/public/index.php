
<?php

// Start session
session_start();

// Register autoloading
spl_autoload_register(function ($className) {

    $prefix = 'App\\';
    $baseDirectory = dirname(__DIR__) . '/app/';

    // Check whether the class uses the App namespace
    $prefixLength = strlen($prefix);

    if (strncmp($prefix, $className, $prefixLength) !== 0) {
        return;
    }

    // Get the relative class name
    $relativeClass = substr($className, $prefixLength);

    // Convert namespace separators into directory separators
    $file = $baseDirectory
          . str_replace('\\', '/', $relativeClass)
          . '.php';

    // Load the class file
    if (file_exists($file)) {
        require $file;
    }
});

// Load Router
use App\Core\Router;

// Dispatch request
$router = new Router();
$router->dispatch();
