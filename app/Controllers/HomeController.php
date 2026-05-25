<?php
    namespace App\Controllers;

    use App\Core\View;
    use App\Model\Poli;
    use App\Model\Antrian;
    class HomeController
    {
        public function index()
        {
            $poli = Poli::all(true);
            $live = Antrian::liveQueue();

            View::render('home/index', [
                "poli" => $poli,
                "live" => $live,
            ], 'main');
        }
    }
