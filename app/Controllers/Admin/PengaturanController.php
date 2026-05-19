<?php
namespace App\Controllers\Admin;

use App\Core\View;

class PengaturanController
{
    public function index(): void
    {
        View::render('admin/pengaturan/index', [], 'main');
    }

    public function save(): void {}
}
