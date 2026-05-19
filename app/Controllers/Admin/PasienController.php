<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\User;

class PasienController
{
    private const BASE = '/santri-belajar/public';

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');

        View::render('admin/pasien/index', [
            'rows' => $keyword === '' ? User::all(200) : $this->search($keyword),
            'keyword' => $keyword,
        ], 'main');
    }

    public function delete($id): void
    {
        $id = (int)$id;

        if ($id <= 0) {
            $this->flash('warn', 'ID pasien tidak valid.');
            $this->redirect('/admin/pasien');
        }

        $st = db()->prepare('DELETE FROM users WHERE id = ?');
        $st->execute([$id]);

        $this->flash('ok', 'Data pasien berhasil dihapus.');
        $this->redirect('/admin/pasien');
    }

    private function search(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        $st = db()->prepare('SELECT * FROM users
                             WHERE name LIKE ? OR nik LIKE ? OR email LIKE ? OR phone LIKE ?
                             ORDER BY created_at DESC
                             LIMIT 200');
        $st->execute([$like, $like, $like, $like]);

        return $st->fetchAll();
    }

    private function flash(string $kind, string $message): void
    {
        $_SESSION['flash'] = compact('kind', 'message');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . self::BASE . $path);
        exit;
    }
}
