<?php
namespace App\Controllers;

use App\Core\View;

class AntreanController
{
    public function daftar(): void
    {
        View::render('antrean/daftar', [], 'main');
    }

    public function daftarProcess(): void
    {
    }

    public function status(): void
    {
        View::render('antrean/status', [], 'main');
    }

    public function cancel(): void
    {
    }

    public function riwayat(): void
    {
        View::render('antrean/riwayat', [], 'main');
    }
}
