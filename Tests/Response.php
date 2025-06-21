<?php

namespace App\Http;


class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function json(array $data): self
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($data));
        return $this;
    }

    public function html(string $html): self
    {
        $this->setHeader('Content-Type', 'text/html');
        $this->setBody($html);
        return $this;
    }

    public function text(string $text): self
    {
        $this->setHeader('Content-Type', 'text/plain');
        $this->setBody($text);
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
