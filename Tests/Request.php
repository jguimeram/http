<?php

namespace App\Http;

class Request
{
    private array $params = [];
    private array $query;
    private array $post;
    private array $headers;
    private string $method;
    private string $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query = $_GET;
        $this->post = $_POST;
        $this->headers = $this->getAllHeaders();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? $default;
    }

    public function getPost(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function getHeader(string $name, $default = null)
    {
        return $this->headers[strtolower($name)] ?? $default;
    }

    public function setParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function getParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    private function getAllHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}
