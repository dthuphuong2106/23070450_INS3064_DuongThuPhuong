<?php

namespace App\Core;

use App\Controllers\ContactController;

class Router
{
    public function dispatch(): void
    {
        $route = $_GET['route'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $controller = new ContactController();

        if ($route === '/' && $method === 'GET') {
            $controller->index();
            return;
        }

        if ($route === '/create' && $method === 'GET') {
            $controller->create();
            return;
        }

        if ($route === '/store' && $method === 'POST') {
            $controller->store();
            return;
        }

        if ($route === '/edit' && $method === 'GET') {
            $controller->edit();
            return;
        }

        if ($route === '/update' && $method === 'POST') {
            $controller->update();
            return;
        }

        if ($route === '/delete' && $method === 'POST') {
            $controller->delete();
            return;
        }

        http_response_code(404);
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p><a href="?route=%2F">Back to contacts</a></p>';
    }
}
