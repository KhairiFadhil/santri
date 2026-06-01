<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\User;
use App\Model\Antrian;
class AuthController extends \App\Core\Controller
{
    public function showLogin(): void
    {
        $aktif = Antrian::liveQueue();
        View::render('auth/login', [
            'error' => '',
            'email' => '',
            'aktif' => $aktif,
        ], 'auth');
    }

    public function login(): void
    {
        // ambil input
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $aktif = Antrian::liveQueue();
        // ada yg kosong
        if (!$email || !$password) {
            View::render('auth/login', [
                'error' => 'Email dan kata sandi wajib diisi.',
                'email' => $email,
                'aktif' => $aktif
            ], 'auth');
            return;
        }

        // cek db
        $user = User::authenticate($email, $password);

        // gak cocok
        if (!$user) {
            View::render('auth/login', [
                'error' => 'Email atau kata sandi salah.',
                'email' => $email,
                'aktif' => $aktif
            ], 'auth');
            return;
        }

        // sukses, simpan session
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'nik'   => $user['nik'],
        ];

        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        // form kosongan
        View::render('auth/register', [
            'errors' => [],
            'form'   => $this->emptyForm(),
        ], 'auth');
    }

    public function register(): void
    {
        // ambil input
        $form = [];
        foreach ($this->emptyForm() as $key => $_) {
            $form[$key] = trim($_POST[$key] ?? '');
        }
        $password = $_POST['password'] ?? '';

        // validasi
        $errors = [];
        if (!$form['name'])                                      $errors[] = 'Nama wajib diisi.';
        if (!preg_match('/^\d{16}$/', $form['nik']))             $errors[] = 'NIK harus 16 digit angka.';
        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL))  $errors[] = 'Format email tidak valid.';
        if (strlen($password) < 8)                               $errors[] = 'Password minimal 8 karakter.';

        // cek duplikat
        if (!$errors) {
            if (User::existsByEmail($form['email'])) $errors[] = 'Email sudah terdaftar.';
            if (User::existsByNik($form['nik']))     $errors[] = 'NIK sudah terdaftar.';
        }

        if ($errors) {
            View::render('auth/register', compact('errors', 'form'), 'auth');
            return;
        }

        // simpan ke db
        User::create([
            'name'           => $form['name'],
            'nik'            => $form['nik'],
            'email'          => $form['email'],
            'password'       => $password,
            'phone'          => $form['phone'] ?: null,
            'gender'         => $form['gender'] ?: null,
            'insurance_type' => $form['insurance_type'] ?: 'Umum',
        ]);

        $user = User::findByEmail($form['email']);
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'nik'   => $user['nik'],
            ];
            $this->flash('ok', 'Akun berhasil dibuat. Selamat datang!');
            $this->redirect('/dashboard');
        }

        // fallback
        $this->flash('ok', 'Akun berhasil dibuat. Silakan masuk.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        // hapus cookie browser
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        // destroy session
        session_destroy();

        $this->redirect('/login');
    }

    // template field default
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
