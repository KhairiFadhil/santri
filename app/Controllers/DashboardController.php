<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;

class DashboardController
{
    public function index()
    {
        $userId = (int) $_SESSION['user']['id'];

        $aktif = Antrian::antrianAktif($userId);

        $infoAntrian = $aktif ? Antrian::infoAntrian($aktif) : [
            'ahead' => 0,
            'eta_minutes' => 0,
            'calling' => null,
            'calling_number' => 0,
        ];

        View::render('dashboard/dashboard', [
            'title' => 'Dashboard',
            'user'  => $_SESSION['user'],
            'aktif' => $aktif,
            'infoAntrian' => $infoAntrian,
        ], 'main');
    }
}
