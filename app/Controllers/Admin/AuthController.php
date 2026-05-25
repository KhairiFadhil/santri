<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Staff;

class AuthController
{
    private const BASE = '/santri-belajar/public';

    public function showLogin(): void
    {
        View::render('admin/auth/login', [
            'error' => '',
            'email' => '',
        ], 'main');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            View::render('admin/auth/login', [
                'error' => 'Email dan password wajib diisi.',
                'email' => $email,
            ], 'main');
            return;
        }

        $staff = Staff::authenticate($email, $password);

        if (!$staff) {
            View::render('admin/auth/login', [
                'error' => 'Email atau password salah.',
                'email' => $email,
            ], 'main');
            return;
        }

        session_regenerate_id(true);

        $_SESSION['staff'] = [
            'id'    => (int)$staff['id'],
            'name'  => $staff['name'],
            'email' => $staff['email'],
            'role'  => $staff['role'],
            'doctor_id' => isset($staff['doctor_id']) ? (int)$staff['doctor_id'] : null,
        ];
        if ($staff['role'] === 'dokter') {
            $this->redirect('/dokter/loket');
            return;
        }

        $this->redirect('/admin');
    }

    public function logout(): void
    {
        if (isset($_SESSION['staff']['id'])) {
            Staff::setStatus((int)$_SESSION['staff']['id'], 'offline');
        }

        unset($_SESSION['staff']);
        $_SESSION['flash'] = [
            'kind' => 'ok',
            'message' => 'Berhasil logout dari admin.',
        ];

        $this->redirect('/admin/login');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . self::BASE . $path);
        exit;
    }
}
