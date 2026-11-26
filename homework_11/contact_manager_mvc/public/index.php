<?php

session_start();

spl_autoload_register(function (string $className): void {
    $prefix = 'App\\';
    $baseDirectory = dirname(__DIR__) . '/app/';

    if (strncmp($prefix, $className, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($className, strlen($prefix));
    $file = $baseDirectory
          . str_replace('\\', '/', $relativeClass)
          . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

$router = new Router();
$router->dispatch();
