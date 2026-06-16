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

        $lama  = $_POST['current_password'] ?? '';
        $baru  = $_POST['new_password']     ?? '';
        $ulang = $_POST['confirm_password'] ?? '';

        $user = User::findById((int)$id);

        $errors = [];
        if (!$lama)                                            $errors[] = 'Kata sandi saat ini wajib diisi.';
        if (!$baru)                                            $errors[] = 'Kata sandi baru wajib diisi.';
        if (!$ulang)                                           $errors[] = 'Konfirmasi kata sandi wajib diisi.';
        if ($baru && strlen($baru) < 8)                        $errors[] = 'Kata sandi baru minimal 8 karakter.';
        if ($baru && $ulang && $baru !== $ulang)              $errors[] = 'Kata sandi baru dan konfirmasi tidak cocok.';
        if ($lama && !password_verify($lama, $user['password_hash'])) $errors[] = 'Kata sandi saat ini salah.';

        if ($errors) {
            View::render('profile/index', [
                'user'   => $user,
                'errors' => $errors,
                'flash'  => null,
            ], 'main');
            return;
        }

        User::updatePassword((int)$id, $baru);

        $this->flash('ok', 'Kata sandi berhasil diganti.');
        $this->redirect('/profile');
    }
}
