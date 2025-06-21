<?php

namespace App\Http;

class Router
{
    private array $routes;

    public function get(string $path, callable $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    private function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    private function getPath()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    }

    private function sendResponse(int $code, ?string $message, string $contentType = "text/plain")
    {
        http_response_code($code);
        header('Content-Type: ' . $contentType);
        echo ($message) ? $message : "not message";
    }

    public function dispatch()
    {
        $method = $this->getRequestMethod();
        $path = $this->getPath();

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
