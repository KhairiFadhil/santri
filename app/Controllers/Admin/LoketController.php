<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Poli;

class LoketController
{
    public function index(): void
    {
        $poliId = isset($_GET['poli_id']) && $_GET['poli_id'] !== '' ? (int)$_GET['poli_id'] : null;

        View::render('admin/loket/index', [
            'poli'     => Poli::all(),
            'filter'   => ['poli_id' => $poliId],
            'waiting'  => Antrian::listActive($poliId, 'wait'),
            'called'   => Antrian::listActive($poliId, 'call'),
            'progress' => Antrian::listActive($poliId, 'progress'),
            'done'     => Antrian::listHariIni($poliId, 'done'),
        ], 'admin');
    }
}
