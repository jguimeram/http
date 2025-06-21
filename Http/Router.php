<?php

namespace App\Http;

use App\Http\Request;

class Router
{
    private array $routes;

    public function get(string $path, callable $handler): void
    {
        $this->route('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->route('POST', $path, $handler);
    }

    private function route(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }


    private function sendResponse(int $code, ?string $message, string $contentType = "text/plain")
    {
        http_response_code($code);
        header('Content-Type: ' . $contentType);
        echo ($message) ? $message : "not message";
    }

    public function dispatch()
    {
        $request = new Request;

        $method = $request->getMethod();
        $path = $request->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            if (is_callable($handler)) {
                $handler();
            } else {
                $this->sendResponse(code: 500, message: 'Internal server error');
            }
        } else {
            $this->sendResponse(code: 404, message: 'Not found');
        }
    }
}
