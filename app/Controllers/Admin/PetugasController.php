<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Staff;

class PetugasController
{
    private const BASE = '/santri-belajar/public';
    private const ROLES = ['admin', 'petugas', 'manajer'];
    private const STATUSES = ['online', 'break', 'offline'];

    public function index(): void
    {
        View::render('admin/petugas/index', [
            'rows' => Staff::all(),
        ], 'main');
    }

    public function create(): void
    {
        View::render('admin/petugas/form', [
            'errors' => [],
            'form' => $this->emptyForm(),
            'mode' => 'create',
            'roles' => self::ROLES,
            'statuses' => self::STATUSES,
        ], 'main');
    }

    public function store(): void
    {
        $form = $this->formFromPost();
        $errors = $this->validate($form, true);

        if (!$errors && Staff::findByEmail($form['email'])) {
            $errors[] = 'Email petugas sudah digunakan.';
        }

        if ($errors) {
            View::render('admin/petugas/form', [
                'errors' => $errors,
                'form' => $form,
                'mode' => 'create',
                'roles' => self::ROLES,
                'statuses' => self::STATUSES,
            ], 'main');
            return;
        }

        Staff::create($form);
        $this->flash('ok', 'Data petugas berhasil ditambahkan.');
        $this->redirect('/admin/petugas');
    }

    public function edit($id): void
    {
        $staff = Staff::findById((int)$id);

        if (!$staff) {
            $this->flash('warn', 'Data petugas tidak ditemukan.');
            $this->redirect('/admin/petugas');
        }

        $staff['password'] = '';

        View::render('admin/petugas/form', [
            'errors' => [],
            'form' => $staff,
            'mode' => 'edit',
            'roles' => self::ROLES,
            'statuses' => self::STATUSES,
        ], 'main');
    }

    public function update($id): void
    {
        $id = (int)$id;
        $staff = Staff::findById($id);

        if (!$staff) {
            $this->flash('warn', 'Data petugas tidak ditemukan.');
            $this->redirect('/admin/petugas');
        }

        $form = $this->formFromPost();
        $errors = $this->validate($form, false);
        $sameEmail = Staff::findByEmail($form['email']);

        if (!$errors && $sameEmail && (int)$sameEmail['id'] !== $id) {
            $errors[] = 'Email petugas sudah digunakan.';
        }

        if ($errors) {
            $form['id'] = $id;
            View::render('admin/petugas/form', [
                'errors' => $errors,
                'form' => $form,
                'mode' => 'edit',
                'roles' => self::ROLES,
                'statuses' => self::STATUSES,
            ], 'main');
            return;
        }

        $data = [
            'name' => $form['name'],
            'email' => $form['email'],
            'role' => $form['role'],
            'loket' => $form['loket'] ?: null,
            'shift' => $form['shift'] ?: null,
            'status' => $form['status'],
        ];

        if ($form['password'] !== '') {
            $data['password'] = $form['password'];
        }

        Staff::update($id, $data);
        $this->flash('ok', 'Data petugas berhasil diperbarui.');
        $this->redirect('/admin/petugas');
    }

    public function delete($id): void
    {
        $id = (int)$id;

        if (isset($_SESSION['staff']['id']) && (int)$_SESSION['staff']['id'] === $id) {
            $this->flash('warn', 'Akun yang sedang login tidak boleh dihapus.');
            $this->redirect('/admin/petugas');
        }

        try {
            Staff::delete($id);
            $this->flash('ok', 'Data petugas berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->flash('warn', 'Petugas tidak bisa dihapus karena masih terhubung dengan antrean.');
        }

        $this->redirect('/admin/petugas');
    }

    private function emptyForm(): array
    {
        return [
            'name' => '',
            'email' => '',
            'password' => '',
            'role' => 'petugas',
            'loket' => '',
            'shift' => '',
            'status' => 'offline',
        ];
    }

    private function formFromPost(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => trim($_POST['role'] ?? 'petugas'),
            'loket' => trim($_POST['loket'] ?? ''),
            'shift' => trim($_POST['shift'] ?? ''),
            'status' => trim($_POST['status'] ?? 'offline'),
        ];
    }

    private function validate(array $form, bool $requirePassword): array
    {
        $errors = [];

        if ($form['name'] === '') {
            $errors[] = 'Nama petugas wajib diisi.';
        }

        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

        if (!in_array($form['role'], self::ROLES, true)) {
            $errors[] = 'Role tidak valid.';
        }

        if (!in_array($form['status'], self::STATUSES, true)) {
            $errors[] = 'Status tidak valid.';
        }

        if ($requirePassword && strlen($form['password']) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        }

        if (!$requirePassword && $form['password'] !== '' && strlen($form['password']) < 8) {
            $errors[] = 'Password baru minimal 8 karakter.';
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
