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



    public function dispatch()
    {
        $request = new Request;
        $response = new Response;

        $method = $request->getMethod();
        $path = $request->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            if (is_callable($handler)) {
                $handler();
            } else {
                $response->setStatusCode(500)->setHeader('text-plain')->setBody('Internal server error');
                $response->send();
            }
        } else {
            $response->setStatusCode(400)->setHeader('text-plain')->setBody('Not found');
            $response->send();
        }
    }
}
