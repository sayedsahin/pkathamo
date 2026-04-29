<?php
namespace App\Systems;

class Redirect
{
    protected string $url = '';
    protected array $data = [];

    public function __construct(string $url = '')
    {
        $this->to($url);
    }

    public function to(string $url = ''): self
    {
        $this->url = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        return $this;
    }

    public function back(): self
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer && $this->isSameDomain($referer)) {
            $this->url = $referer;
        } else {
            $this->url = BASE_URL;
        }
        return $this;
    }

    public function with(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function toResponse(): \App\Systems\Response
    {
        // Store data in session if available
        if (!empty($this->data)) {
            $_SESSION['flash'] = $this->data;
        }

        // Return Response object instead of calling exit()
        return response('', 302)->header('Location', $this->url);
    }

    private function isSameDomain(string $url): bool
    {
        $refererHost = parse_url($url, PHP_URL_HOST);
        $baseHost = parse_url(BASE_URL, PHP_URL_HOST);
        return $refererHost === $baseHost;
    }
}