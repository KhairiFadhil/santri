<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;
use App\Model\Poli;
use App\Model\Staff;
use App\Model\User;

class DashboardController
{
    public function index(): void
    {
        $stats = [
            'poli' => Poli::count(),
            'poli_buka' => Poli::count(true),
            'dokter_aktif' => Dokter::count(),
            'pasien' => $this->countTable('users'),
            'petugas' => $this->countTable('staff'),
            'antrean_hari_ini' => Antrian::jumlahAntrianHariIni(),
            'menunggu' => Antrian::countByStatusHariIni('wait'),
            'dipanggil' => Antrian::countByStatusHariIni('call'),
            'diproses' => Antrian::countByStatusHariIni('progress'),
            'selesai' => Antrian::countByStatusHariIni('done'),
            'dilewati' => Antrian::countByStatusHariIni('skip'),
            'batal' => Antrian::countByStatusHariIni('cancel'),
        ];

        View::render('admin/dashboard/index', [
            'stats' => $stats,
            'antrean' => Antrian::listHariIni(),
            'poli' => Poli::all(),
            'dokter' => Dokter::all(true),
            'staff' => Staff::all(),
            'pasien' => User::all(10),
        ], 'admin');
    }

    private function countTable(string $table): int
    {
        $allowed = ['users', 'staff', 'poli', 'doctors', 'queues'];
        if (!in_array($table, $allowed, true)) {
            return 0;
        }

        return (int)db()->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    }
}
