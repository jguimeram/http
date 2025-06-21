<?php
require('../bootstrap.php');

use App\Http\Router;
use App\Http\Request;
use App\Http\Response;

$router = new Router;
// Usage

// Function
$router->get('/', function (Request $request, Response $response) {
    return $response->text('hello, world');
});

// Anonymous function
$router->get('/about', function (Request $request, Response $response) {
    return $response->json(['message' => 'User created', 'name' => 'name']);
});



$router->dispatch();
//echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
