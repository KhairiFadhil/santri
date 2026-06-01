<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Poli;

class PoliController extends \App\Core\Controller
{
    public function index(): void
    {
        View::render('admin/poli/index', [
            'rows' => Poli::all(),
        ], 'admin');
    }

    public function create(): void
    {
        View::render('admin/poli/form', [
            'errors' => [],
            'form' => $this->emptyForm(),
            'mode' => 'create',
        ], 'admin');
    }

    public function store(): void
    {
        $form = $this->formFromPost();
        $errors = $this->validate($form);

        if (!$errors && Poli::findByCode($form['code'])) {
            $errors[] = 'Kode poli sudah digunakan.';
        }

        if ($errors) {
            View::render('admin/poli/form', [
                'errors' => $errors,
                'form' => $form,
                'mode' => 'create',
            ], 'admin');
            return;
        }

        Poli::create($form);
        $this->flash('ok', 'Data poli berhasil ditambahkan.');
        $this->redirect('/admin/poli');
    }

    public function edit($id): void
    {
        $poli = Poli::findById((int)$id);

        if (!$poli) {
            $this->flash('warn', 'Data poli tidak ditemukan.');
            $this->redirect('/admin/poli');
        }

        View::render('admin/poli/form', [
            'errors' => [],
            'form' => $poli,
            'mode' => 'edit',
        ], 'admin');
    }

    public function update($id): void
    {
        $id = (int)$id;
        $poli = Poli::findById($id);

        if (!$poli) {
            $this->flash('warn', 'Data poli tidak ditemukan.');
            $this->redirect('/admin/poli');
        }

        $form = $this->formFromPost();
        $errors = $this->validate($form);
        $sameCode = Poli::findByCode($form['code']);

        if (!$errors && $sameCode && (int)$sameCode['id'] !== $id) {
            $errors[] = 'Kode poli sudah digunakan.';
        }

        if ($errors) {
            $form['id'] = $id;
            View::render('admin/poli/form', [
                'errors' => $errors,
                'form' => $form,
                'mode' => 'edit',
            ], 'admin');
            return;
        }

        Poli::update($id, $form);
        $this->flash('ok', 'Data poli berhasil diperbarui.');
        $this->redirect('/admin/poli');
    }

    public function delete($id): void
    {
        try {
            Poli::delete((int)$id);
            $this->flash('ok', 'Data poli berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->flash('warn', 'Poli tidak bisa dihapus karena masih terhubung dengan data dokter atau antrean.');
        }

        $this->redirect('/admin/poli');
    }

    private function emptyForm(): array
    {
        return [
            'code'    => '',
            'name'    => '',
            'sub'     => '',
            'icon'    => 'Stethoscope',
            'is_open' => 1,
        ];
    }

    private function formFromPost(): array
    {
        return [
            'code'    => strtoupper(substr(trim($_POST['code'] ?? ''), 0, 3)),
            'name'    => trim($_POST['name'] ?? ''),
            'sub'     => trim($_POST['sub'] ?? ''),
            'icon'    => trim($_POST['icon'] ?? 'Stethoscope'),
            'is_open' => isset($_POST['is_open']) ? 1 : 0,
        ];
    }

    private function validate(array $form): array
    {
        $errors = [];
        if (!preg_match('/^[A-Z]{1,3}$/', $form['code'])) {
            $errors[] = 'Kode poli wajib 1 sampai 3 huruf kapital.';
        }
        if ($form['name'] === '') {
            $errors[] = 'Nama poli wajib diisi.';
        }
        return $errors;
    }
}
