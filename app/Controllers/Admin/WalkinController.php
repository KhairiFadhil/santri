<?php
namespace App\Controllers\Admin;

use App\Core\View;

class WalkinController
{
    public function index(): void
    {
        View::render('admin/walkin/index', [], 'main');
    }

    public function store(): void
    {
    }
}
