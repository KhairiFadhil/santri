<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Pengaturan;

class PengaturanController
{
    private const BASE = '/santri-belajar/public';

    public function index(): void
    {
        View::render('admin/pengaturan/index', [
            'settings' => Pengaturan::all(),
            'errors' => [],
        ], 'main');
    }

    public function save(): void
    {
        $blocked = ['csrf_token', '_token', 'submit'];
        $saved = 0;

        foreach ($_POST as $key => $value) {
            $key = trim((string)$key);

            if ($key === '' || in_array($key, $blocked, true)) {
                continue;
            }

            if (!preg_match('/^[a-zA-Z0-9_.-]{1,60}$/', $key)) {
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            Pengaturan::set($key, trim((string)$value));
            $saved++;
        }

        $_SESSION['flash'] = [
            'kind' => 'ok',
            'message' => $saved > 0 ? 'Pengaturan berhasil disimpan.' : 'Tidak ada pengaturan yang berubah.',
        ];

        $this->redirect('/admin/pengaturan');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . self::BASE . $path);
        exit;
    }
}
