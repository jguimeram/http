<?php
require('../bootstrap.php');

use App\Http\Router;

$router = new Router;

$router->get('/', function () {
    http_response_code(200);
    header('Content-Type: text/plain'); //plain text, not html
    echo 'Welcome to the home page';
});

$router->get('/about', function () {
    http_response_code(200);
    header('Content-Type: text/html');
    echo '<h1>About Page</h1><p>This is the about page.</p>';
});

$router->get('/json', function () {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Hello from JSON API', 'status' => 'success']);
});

$router->get('/redirect', function () {
    http_response_code(302);
    header('Location: /');
    echo 'Redirecting to homepage...';
});

//open xml version of echo
$router->get('/xml', function () {
    http_response_code(200);
    header('Content-Type: application/xml');
    echo '<?xml version="1.0"?><response><message>Hello XML</message></response>';
});

//downloads a file 'sample.txt' with the content of echo.
$router->get('/download', function () {
    http_response_code(200);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="sample.txt"');
    echo 'This is a downloadable file content';
});



$router->dispatch();
//echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
