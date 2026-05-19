<?php
namespace App\Controllers\Admin;

use App\Core\View;

class PetugasController
{
    public function index(): void { View::render('admin/petugas/index', [], 'main'); }
    public function create(): void { View::render('admin/petugas/form', [], 'main'); }
    public function store(): void {}
    public function edit($id): void { View::render('admin/petugas/form', [], 'main'); }
    public function update($id): void {}
    public function delete($id): void {}
}
