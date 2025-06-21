<?php

namespace App\Http;

use App\Http\Request;
use App\Http\Response;
use Exception;


class Router
{
    private array $routes = [];



    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }



    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function run(): void
    {
        $request = new Request();
        $response = new Response();

        $method = $request->getMethod();
        $path = $request->getPath();

        // Look for exact match first
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $this->executeHandler($handler, $request, $response);
            return;
        }

        // Look for route with parameters
        foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
            if ($this->matchRoute($routePath, $path, $request)) {
                $this->executeHandler($handler, $request, $response);
                return;
            }
        }

        // No route found
        $response->setStatusCode(404)->text('Not Found')->send();
    }

    private function matchRoute(string $routePath, string $requestPath, Request $request): bool
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches)) {
            // Extract parameter names
            preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);

            // Set parameters in request
            for ($i = 0; $i < count($paramNames[1]); $i++) {
                $paramName = $paramNames[1][$i];
                $paramValue = $matches[$i + 1];
                $request->setParam($paramName, $paramValue);
            }

            return true;
        }

        return false;
    }

    private function executeHandler(callable $handler, Request $request, Response $response): void
    {
        try {
            // Use call_user_func to handle any type of callable
            $result = call_user_func($handler, $request, $response);

            // Handle different return types
            if ($result instanceof Response) {
                $result->send();
            } elseif (is_array($result)) {
                $response->json($result)->send();
            } elseif (is_string($result)) {
                $response->text($result)->send();
            } elseif ($result === null) {
                // Handler already sent response or used the passed response object
                $response->send();
            } else {
                $response->text((string)$result)->send();
            }
        } catch (Exception $e) {
            $response->setStatusCode(500)->text('Internal Server Error')->send();
        }
    }
}

/* // Usage examples:

// Function handler
function homeHandler(Request $request, Response $response): string
{
    return "Welcome to the home page!";
}

// Class method handler
class UserController
{
    public function show(Request $request, Response $response): array
    {
        $id = $request->getParam('id');
        return ['user_id' => $id, 'name' => 'John Doe'];
    }

    public static function create(Request $request, Response $response): void
    {
        $name = $request->getPost('name');
        $response->json(['message' => 'User created', 'name' => $name]);
    }
}

// Usage
$router = new Router();

// Function
$router->get('/', 'homeHandler');

// Anonymous function
$router->get('/about', function (Request $request, Response $response) {
    return "About page";
});

// Class method
$controller = new UserController();
$router->get('/users/{id}', [$controller, 'show']);

// Static method
$router->post('/users', [UserController::class, 'create']);

// Run the router
$router->run();
 */