<?php

namespace App\Controllers;

use App\Core\View;
use App\Model\User;

class ProfileController
{
    public function index(): void
    {
        // ambil user dari db
        $id = $_SESSION['user']['id'] ?? null;
        $user = User::findById((int)$id);

        View::render('profile/index', [
            'user'   => $user,
            'errors' => [],
            'flash'  => $_SESSION['flash'] ?? null,
        ], 'main');

        // hapus flash
        unset($_SESSION['flash']);
    }

    public function update(): void
    {
        $id = $_SESSION['user']['id'] ?? null;
        $user = User::findById((int)$id);

        // input
        $data = [
            'name'  => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'birth' => trim($_POST['birth'] ?? '') ?: null,
        ];

        // validasi
        $errors = [];
        if (!$data['name']) $errors[] = 'Nama wajib diisi.';

        if ($errors) {
            // sticky form
            View::render('profile/index', [
                'user'   => array_merge($user, $data),
                'errors' => $errors,
                'flash'  => null,
            ], 'main');
            return;
        }

        User::update((int)$id, $data);

        // sync session
        $_SESSION['user']['name'] = $data['name'];

        $_SESSION['flash'] = ['kind' => 'ok', 'message' => 'Profil berhasil diperbarui.'];
        header('Location: /santri-belajar/public/profile');
        exit;
    }

    public function changePassword(): void
    {
        $id = $_SESSION['user']['id'] ?? null;

        // input
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // butuh password_hash dari db
        $user = User::findById((int)$id);

        // validasi
        $errors = [];
        if (!$current)                                            $errors[] = 'Kata sandi saat ini wajib diisi.';
        if (!$new)                                                $errors[] = 'Kata sandi baru wajib diisi.';
        if (!$confirm)                                            $errors[] = 'Konfirmasi kata sandi wajib diisi.';
        if ($new && strlen($new) < 8)                             $errors[] = 'Kata sandi baru minimal 8 karakter.';
        if ($new && $confirm && $new !== $confirm)                $errors[] = 'Kata sandi baru dan konfirmasi tidak cocok.';
        if ($current && !password_verify($current, $user['password_hash'])) $errors[] = 'Kata sandi saat ini salah.';

        if ($errors) {
            View::render('profile/index', [
                'user'   => $user,
                'errors' => $errors,
                'flash'  => null,
            ], 'main');
            return;
        }

        User::updatePassword((int)$id, $new);

        $_SESSION['flash'] = ['kind' => 'ok', 'message' => 'Kata sandi berhasil diganti.'];
        header('Location: /santri-belajar/public/profile');
        exit;
    }
}
