<?php
namespace App\Core;

class Request
{
    public string $method;
    public string $uri;
    private ?string $path = null;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->uri    = $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function path(): string
    {
        if ($this->path !== null) return $this->path;
        $uri = parse_url($this->uri, PHP_URL_PATH) ?? '/';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $base   = rtrim(str_replace('\\', '/', dirname($script)), '/');
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . ltrim($uri, '/');
        return $this->path = $uri === '' ? '/' : $uri;
    }

    public function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    public function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    public function isPost(): bool { 
        return $this->method === 'POST'; 
        }

    public function isGet():  bool { 
        return $this->method === 'GET'; 
        }

    public function bearer(): ?string
    {
        $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return preg_match('/Bearer\s+(.+)/i', $h, $m) ? $m[1] : null;
    }
}
