<?php

namespace App\Http;

use App\Http\Request;
use Throwable;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->route('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->route('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->route('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->route('DELETE', $path, $handler);
    }


    private function route(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function executeHandler(callable $handler, Request $request, Response $response): void
    {
        try {
            //result get the return from index.php and has  to process it
            $result = call_user_func($handler, $request, $response);
            debug($result);
        } catch (Throwable $e) {
            $response->setStatusCode(500)->text('Internal Server Error')->send();
        }
    }


    public function dispatch()
    {
        $request = new Request;
        $response = new Response;

        $method = $request->getMethod();
        $path = $request->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $this->executeHandler($handler, $request, $response);
            return;
        } else {
            $response->setStatusCode(400)->text('Not found')->send();
        }
    }
}
