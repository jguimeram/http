<?php

namespace App\Http;


class Response
{

    private int $statusCode = 200;
    private string $header;
    private string $body;

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this; //chain methods
    }

    public function setHeader(string $name): self
    {
        $this->header = $name;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function text(string $text): self
    {
        $this->setHeader('text/plain');
        $this->setBody($text);
        return $this;
    }

    public function json(array $json): self
    {
        $this->setHeader('application/json');
        $this->setBody(json_encode($json));
        return $this;
    }

    public function html(string $html): self
    {
        $this->setHeader('text/html');
        $this->setBody($html);
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: ' . $this->header);
        echo $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
