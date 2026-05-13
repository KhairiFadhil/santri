namespace App\Core;

abstract class Controller
{
    public Request $request;
    public Session $session;

    protected function view(string $view, array $data = [], ?string $layout = null, array $layoutData = []): void
    {
        View::render($view, $data, $layout, $layoutData);
    }

    protected function redirect(string $url): void
    {
        redirect($url);
    }

    protected function back(string $fallback = '/'): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? null;
        redirect($ref ?: url($fallback));
    }

    protected function json($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function jsonError(string $msg, int $code = 400, array $extra = []): void
    {
        $this->json(array_merge(['ok' => false, 'error' => $msg], $extra), $code);
    }

    protected function jsonOk(array $data = []): void
    {
        $this->json(array_merge(['ok' => true], $data));
    }

    protected function requireCsrf(): void
    {
        if (!$this->session->csrfCheck()) {
            throw new HttpException(419, 'Token CSRF tidak valid.');
        }
    }

    /** Validasi sederhana - return array errors (key=field, value=msg) */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $ruleStr) {
            $value = $data[$field] ?? null;
            $rules = explode('|', $ruleStr);
            foreach ($rules as $rule) {
                if ($rule === 'required' && (is_null($value) || $value === '')) {
                    $errors[$field] = 'Wajib diisi.'; break;
                }
                if ($rule === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Format email tidak valid.'; break;
                }
                if ($rule === 'nik' && $value && !preg_match('/^\d{16}$/', $value)) {
                    $errors[$field] = 'NIK harus 16 digit angka.'; break;
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if ($value && strlen((string)$value) < $min) {
                        $errors[$field] = "Minimal $min karakter."; break;
                    }
                }
            }
        }
        return $errors;
    }
}
