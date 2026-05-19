<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\User;

class AuthController
{
    public function showLogin(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: /santri-belajar/public/dashboard');
            exit;
        }

        View::render('auth/login', [
            'error' => '',
            'email' => '',
        ], 'main');
    }

    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            View::render('auth/login', [
                'error' => 'Email dan kata sandi wajib diisi.',
                'email' => $email,
            ], 'main');
            return;
        }

        $user = User::authenticate($email, $password);

        if (!$user) {
            View::render('auth/login', [
                'error' => 'Email atau kata sandi salah.',
                'email' => $email,
            ], 'main');
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'nik'   => $user['nik'],
        ];

        header('Location: /santri-belajar/public/dashboard');
        exit;
    }

    public function showRegister(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: /santri-belajar/public/dashboard');
            exit;
        }

        View::render('auth/register', [
            'errors' => [],
            'form'   => $this->emptyForm(),
        ], 'main');
    }

    public function register(): void
    {
        $form = [];
        foreach ($this->emptyForm() as $key => $_) {
            $form[$key] = trim($_POST[$key] ?? '');
        }
        $password = $_POST['password'] ?? '';

        $errors = [];

        if (!$form['name'])                                      $errors[] = 'Nama wajib diisi.';
        if (!preg_match('/^\d{16}$/', $form['nik']))             $errors[] = 'NIK harus 16 digit angka.';
        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL))  $errors[] = 'Format email tidak valid.';
        if (strlen($password) < 8)                               $errors[] = 'Password minimal 8 karakter.';

        if (!$errors) {
            if (User::existsByEmail($form['email'])) $errors[] = 'Email sudah terdaftar.';
            if (User::existsByNik($form['nik']))     $errors[] = 'NIK sudah terdaftar.';
        }

        if ($errors) {
            View::render('auth/register', compact('errors', 'form'), 'main');
            return;
        }

        User::create([
            'name'           => $form['name'],
            'nik'            => $form['nik'],
            'email'          => $form['email'],
            'password'       => $password,
            'phone'          => $form['phone'] ?: null,
            'gender'         => $form['gender'] ?: null,
            'insurance_type' => $form['insurance_type'] ?: 'Umum',
        ]);

        $_SESSION['flash'] = [
            'kind'    => 'ok',
            'message' => 'Akun berhasil dibuat. Silakan masuk.',
        ];
        header('Location: /santri-belajar/public/login');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();

        header('Location: /santri-belajar/public/login');
        exit;
    }

    private function emptyForm(): array
    {
        return [
            'name'           => '',
            'nik'            => '',
            'email'          => '',
            'phone'          => '',
            'gender'         => '',
            'insurance_type' => 'Umum',
        ];
    }
}
