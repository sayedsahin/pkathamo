<?php

namespace App\Systems;

class Redirect
{
    protected string $url = '';
    protected array $data = [];

    public function __construct(string $url = '', $with = [])
    {
        if (!empty($url)) {
            # code...
            if ($with) {
                $this->with($with);
            }

            if ($url === 'back') {
                $this->back();
                return;
            }

            if ($url !== 'back') {
                $this->to($url);
            }
        }
    }

    public function with(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function to(string $url = ''): void
    {
        $this->url = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        $this->send();
    }

    public function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer && $this->isSameDomain($referer)) {
            $this->url = $referer;
        } else {
            $this->url = BASE_URL;
        }

        $this->send();
    }

    public function send()
    {
        if (!empty($this->data)) {
            $_SESSION['flash'] = $this->data;
        }
        http_response_code(302);
        header('Location: ' . $this->url);
        exit;
    }

    private function isSameDomain(string $url): bool
    {
        $refererHost = parse_url($url, PHP_URL_HOST);
        $baseHost = parse_url(BASE_URL, PHP_URL_HOST);
        return $refererHost === $baseHost;
    }
}
