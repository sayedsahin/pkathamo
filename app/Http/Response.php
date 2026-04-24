<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    protected int $status = 200;
    protected array $headers = [];
    protected string $content = '';

    public function __construct(string $content = '', int $status = 200)
    {
        $this->content = $content;
        $this->status  = $status;
    }

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /* -----------------------
       Getters for Async Support
    ----------------------- */

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /* -----------------------
       Send to Output Buffer
    ----------------------- */

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }

        echo $this->content;
    }
}
