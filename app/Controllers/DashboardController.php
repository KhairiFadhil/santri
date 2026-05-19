<?php
namespace App\Controllers;

use App\Core\View;

class DashboardController
{
    public function index()
    {
        View::render('dashboard/dashboard', [
            'title' => 'Dashboard',
            'user'  => $_SESSION['user'],
        ], 'main');
    }
}
