<?php

namespace App\Controllers;

use App\Core\View;

class ProfileController
{
    public function index(): void
    {
        View::render('profile/index', [
            'user' => $_SESSION['user'],
        ], 'main');
    }

    public function update(): void
    {
    }

    public function changePassword(): void
    {
    }
}
