<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Poli;

class AntreanController
{
    public function index(): void
    {
        $poliId = isset($_GET['poli_id']) && $_GET['poli_id'] !== '' ? (int)$_GET['poli_id'] : null;
        $status = trim($_GET['status'] ?? '');
        $status = $status !== '' ? $status : null;

        View::render('admin/antrean/index', [
            'rows' => Antrian::listHariIni($poliId, $status),
            'poli' => Poli::all(),
            'filter' => [
                'poli_id' => $poliId,
                'status' => $status,
            ],
            'summary' => [
                'wait' => Antrian::countByStatusHariIni('wait'),
                'call' => Antrian::countByStatusHariIni('call'),
                'progress' => Antrian::countByStatusHariIni('progress'),
                'done' => Antrian::countByStatusHariIni('done'),
                'skip' => Antrian::countByStatusHariIni('skip'),
                'cancel' => Antrian::countByStatusHariIni('cancel'),
            ],
        ], 'admin');
    }
}
