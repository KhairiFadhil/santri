<?php
namespace App\Controllers\Admin;

use App\Core\View;

class AntreanController
{
    public function index(): void
    {
        View::render('admin/antrean/index', [], 'main');
    }
}
