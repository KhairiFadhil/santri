<?php
namespace App\Controllers;

use App\Core\View;

class ProfileController
{
    public function index(): void
    {
        View::render('profile/index', [], 'main');
    }

    public function update(): void
    {
    }

    public function changePassword(): void
    {
    }
}
