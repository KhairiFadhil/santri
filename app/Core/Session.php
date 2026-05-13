<?php
namespace App\Core;

// Session + flash + CSRF wrapper

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.use_strict_mode', '1');
            session_start();
        }
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    // ===== User auth (pasien) =====
    public function user(): ?array            { return $_SESSION['user'] ?? null; }
    public function loginUser(array $u): void  { $this->regenerate(); unset($u['password_hash']); $_SESSION['user'] = $u; }
    public function logoutUser(): void         { unset($_SESSION['user']); }

    // ===== Staff auth =====
    public function staff(): ?array            { return $_SESSION['staff'] ?? null; }
    public function loginStaff(array $s): void { $this->regenerate(); unset($s['password_hash']); $_SESSION['staff'] = $s; }
    public function logoutStaff(): void        { unset($_SESSION['staff']); }

    // ===== Flash messages =====
    public function flash(string $kind, string $title, string $sub = ''): void
    {
        $_SESSION['flash'][] = compact('kind','title','sub');
    }

    public function takeFlash(): array
    {
        $f = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $f;
    }

    // ===== CSRF (Cross-Site Request Forgery) =====
    public function csrfToken(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    public function csrfCheck(): bool
    {
        $token = $_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF'] ?? '';
        return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$token);
    }
}
