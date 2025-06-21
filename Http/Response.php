<?php

namespace App\Http;

/*
 private function sendResponse(int $code, ?string $message, string $contentType = "text/plain")
    {
        http_response_code($code);
        header('Content-Type: ' . $contentType);
        echo ($message) ? $message : "not message";
    }
*/


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
