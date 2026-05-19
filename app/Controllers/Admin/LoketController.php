<?php
namespace App\Controllers\Admin;

use App\Core\View;

class LoketController
{
    public function index(): void
    {
        View::render('admin/loket/index', [], 'main');
    }

    public function call(): void {}
    public function skip(): void {}
    public function progress(): void {}
    public function done(): void {}
}
