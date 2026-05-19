<?php
namespace App\Controllers\Admin;

use App\Core\View;

class DashboardController
{
    public function index(): void
    {
        View::render('admin/dashboard/index', [], 'main');
    }
}
