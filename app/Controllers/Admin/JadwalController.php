<?php
namespace App\Controllers\Admin;

use App\Core\View;

class JadwalController
{
    public function index(): void
    {
        View::render('admin/jadwal/index', [], 'main');
    }

    public function upsert(): void {}
    public function delete(): void {}
}
