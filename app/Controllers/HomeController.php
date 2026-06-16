<?php

namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Poli;

class HomeController extends \App\Core\Controller
{
    public function index(): void
    {
        $live = Antrian::antrianLive();
        $poli = Poli::all(true);
        View::render('home/index', [
            'poli' => $poli,
            'live' => $live,
        ], 'main');
    }
}