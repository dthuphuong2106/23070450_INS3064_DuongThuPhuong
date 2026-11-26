
<?php

namespace App\Core;

use App\Controllers\ContactController;

class Router
{
    public function dispatch(): void
    {
        $route = $_GET['route'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        $controller = new ContactController();

        // Display contact list
        if ($route === '/' && $method === 'GET') {
            $controller->index();
            return;
        }

        // Display create form
        if ($route === '/create' && $method === 'GET') {
            $controller->create();
            return;
        }

        // Process create form
        if ($route === '/store' && $method === 'POST') {
            $controller->store();
            return;
        }

        // Display edit form
        if ($route === '/edit' && $method === 'GET') {
            $controller->edit();
            return;
        }

        // Process update form
        if ($route === '/update' && $method === 'POST') {
            $controller->update();
            return;
        }

        // Process delete request
        if ($route === '/delete' && $method === 'POST') {
            $controller->delete();
            return;
        }

        http_response_code(404);
        echo '<h1>404 - Page Not Found</h1>';
    }
}
