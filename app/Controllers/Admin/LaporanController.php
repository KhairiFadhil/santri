<?php
namespace App\Controllers\Admin;

use App\Core\View;

class LaporanController
{
    public function index(): void
    {
        View::render('admin/laporan/index', [], 'main');
    }
}
