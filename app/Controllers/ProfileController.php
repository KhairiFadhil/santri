<?php

namespace App\Controllers;

use App\Core\View;
use App\Model\User;

class ProfileController extends \App\Core\Controller
{
    public function index(): void
    {

        $id = $_SESSION['user']['id'] ?? null;
        $user = User::findById($id) ?? '';
        View::render('profile/index', [
            'user' => $user,
            'errors' => null,
            'flash' => null,
        ], 'main');
    }

    public function update(): void
    {
        $id = $_SESSION['user']['id'] ?? null;
        $user = User::findById((int)$id);

        $data = [
            'name'  => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'birth' => trim($_POST['birth'] ?? '') ?: null,
        ];

        $errors = [];
        if (!$data['name']) $errors[] = 'Nama wajib diisi.';

        if ($errors) {

            View::render('profile/index', [
                'user'   => array_merge($user, $data),
                'errors' => $errors,
                'flash'  => null,
            ], 'main');
            return;
        }

        User::update((int)$id, $data);
        $_SESSION['user']['name'] = $data['name'];

        $this->flash('ok', 'Profil berhasil diperbarui.');
        $this->redirect('/profile');
    }

    public function changePassword(): void
    {
        $id = $_SESSION['user']['id'] ?? null;

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $user = User::findById((int)$id);

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

        $this->flash('ok', 'Kata sandi berhasil diganti.');
        $this->redirect('/profile');
    }
}
