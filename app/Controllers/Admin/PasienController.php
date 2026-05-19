<?php
namespace App\Controllers\Admin;

use App\Core\View;

class PasienController
{
    public function index(): void
    {
        View::render('admin/pasien/index', [], 'main');
    }

    public function delete($id): void {}
}
