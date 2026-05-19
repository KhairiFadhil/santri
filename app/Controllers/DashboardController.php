<?php

namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;

class DashboardController
{
    public function index()
    {
        // Proteksi: hanya untuk user yang sudah login
        if (!isset($_SESSION['user'])) {
            header('Location: /santri-belajar/public/login');
            exit;
        }

        View::render('dashboard/dashboard', [
            'title' => 'Dashboard',
            'user'  => $_SESSION['user'],
        ], 'main');
    }
}
