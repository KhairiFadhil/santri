<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Poli;

class PoliController
{
    public function detail($id): void
    {
        $poli = Poli::findById((int)$id);
        if (!$poli) {
            http_response_code(404);
            echo "<h1>Poli tidak ditemukan</h1>";
            return;
        }
        View::render('poli/detail', ['poli' => $poli], 'main');
    }
}
