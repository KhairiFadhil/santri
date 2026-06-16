<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\User;

class PasienController extends \App\Core\Controller
{
    public function index(): void
    {
        $filter = $_GET['filter'] ?? 'nama';
        $cari   = trim($_GET['q'] ?? '');

        View::render('admin/pasien/index', [
            'rows'    => $cari === '' ? User::all(200) : $this->cariPasien($filter, $cari),
            'filter'  => $filter,
            'keyword' => $cari,
        ], 'admin');
    }

    public function delete($id): void
    {
        $id = (int)$id;

        if ($id <= 0) {
            $this->flash('warn', 'ID pasien tidak valid.');
            $this->redirect('/admin/pasien');
        }

        User::delete($id);

        $this->flash('ok', 'Data pasien berhasil dihapus.');
        $this->redirect('/admin/pasien');
    }

    private function cariPasien($filter, $cari): array
    {
        $kolom = [
            'nama'  => 'name',
            'nik'   => 'nik',
            'email' => 'email',
            'hp'    => 'phone',
        ];
        $kol = $kolom[$filter] ?? 'name';

        $st = db()->prepare("SELECT * FROM users WHERE $kol LIKE ? ORDER BY created_at DESC LIMIT 200");
        $st->execute(['%' . $cari . '%']);

        return $st->fetchAll();
    }
}
