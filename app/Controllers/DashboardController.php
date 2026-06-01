<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;

class DashboardController
{
    public function index()
    {
        $userId = (int) $_SESSION['user']['id'];

        $aktif = Antrian::getAntrianAktif($userId);

        $frontStats = $aktif ? Antrian::frontStats($aktif) : [
            'ahead' => 0,
            'eta_minutes' => 0,
            'calling' => null,
            'calling_number' => 0,
        ];

        View::render('dashboard/dashboard', [
            'title' => 'Dashboard',
            'user'  => $_SESSION['user'],
            'aktif' => $aktif,
            'frontStats' => $frontStats,
        ], 'main');
    }
}
