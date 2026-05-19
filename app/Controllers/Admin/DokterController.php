<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Dokter;
use App\Model\Poli;

class DokterController
{
    private const BASE = '/santri-belajar/public';

    public function index(): void
    {
        View::render('admin/dokter/index', [
            'rows' => Dokter::all(false),
            'poli' => Poli::all(),
        ], 'main');
    }

    public function create(): void
    {
        View::render('admin/dokter/form', [
            'errors' => [],
            'form' => $this->emptyForm(),
            'poli' => Poli::all(),
            'mode' => 'create',
        ], 'main');
    }

    public function store(): void
    {
        $form = $this->formFromPost();
        $errors = $this->validate($form);

        if ($errors) {
            View::render('admin/dokter/form', [
                'errors' => $errors,
                'form' => $form,
                'poli' => Poli::all(),
                'mode' => 'create',
            ], 'main');
            return;
        }

        Dokter::create($form);
        $this->flash('ok', 'Data dokter berhasil ditambahkan.');
        $this->redirect('/admin/dokter');
    }

    public function edit($id): void
    {
        $dokter = Dokter::findById((int)$id);

        if (!$dokter) {
            $this->flash('warn', 'Data dokter tidak ditemukan.');
            $this->redirect('/admin/dokter');
        }

        View::render('admin/dokter/form', [
            'errors' => [],
            'form' => $dokter,
            'poli' => Poli::all(),
            'mode' => 'edit',
        ], 'main');
    }

    public function update($id): void
    {
        $id = (int)$id;
        $dokter = Dokter::findById($id);

        if (!$dokter) {
            $this->flash('warn', 'Data dokter tidak ditemukan.');
            $this->redirect('/admin/dokter');
        }

        $form = $this->formFromPost();
        $errors = $this->validate($form);

        if ($errors) {
            $form['id'] = $id;
            View::render('admin/dokter/form', [
                'errors' => $errors,
                'form' => $form,
                'poli' => Poli::all(),
                'mode' => 'edit',
            ], 'main');
            return;
        }

        Dokter::update($id, $form);
        $this->flash('ok', 'Data dokter berhasil diperbarui.');
        $this->redirect('/admin/dokter');
    }

    public function delete($id): void
    {
        try {
            Dokter::delete((int)$id);
            $this->flash('ok', 'Data dokter berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->flash('warn', 'Dokter tidak bisa dihapus karena masih terhubung dengan jadwal atau antrean.');
        }

        $this->redirect('/admin/dokter');
    }

    private function emptyForm(): array
    {
        return [
            'poli_id' => '',
            'name' => '',
            'specialization' => '',
            'photo' => '',
            'is_active' => 1,
        ];
    }

    private function formFromPost(): array
    {
        return [
            'poli_id' => (int)($_POST['poli_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'specialization' => trim($_POST['specialization'] ?? ''),
            'photo' => trim($_POST['photo'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
    }

    private function validate(array $form): array
    {
        $errors = [];

        if ($form['poli_id'] <= 0 || !Poli::findById($form['poli_id'])) {
            $errors[] = 'Poli wajib dipilih.';
        }

        if ($form['name'] === '') {
            $errors[] = 'Nama dokter wajib diisi.';
        }

        return $errors;
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
