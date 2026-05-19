<?php
namespace App\Controllers\Admin;

use App\Core\View;

class AuthController
{
    public function showLogin(): void
    {
        View::render('admin/auth/login', [], 'main');
    }

    public function login(): void
    {
    }

    public function logout(): void
    {
    }
}
