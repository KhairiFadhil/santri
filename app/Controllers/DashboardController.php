<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;

class DashboardController
{
    public function index()
    {
        $userId = (int)$_SESSION['user']['id'];
        View::render('dashboard/dashboard', [
            'title' => 'Dashboard',
            'user'  => $_SESSION['user'],
            'aktif' => Antrian::getAntrianAktif($userId),
        ], 'main');
    }
}
