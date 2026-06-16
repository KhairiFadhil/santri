<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Dokter;
use App\Model\Poli;
use App\Model\Staff;

class DokterController extends \App\Core\Controller
{
    public function index(): void
    {
        View::render('admin/dokter/index', [
            'rows' => Dokter::all(false),
            'poli' => Poli::all(),
            'akun' => Staff::akunDokter(),
        ], 'admin');
    }

    public function create(): void
    {
        View::render('admin/dokter/form', [
            'errors' => [],
            'form' => $this->emptyForm(),
            'poli' => Poli::all(),
            'mode' => 'create',
        ], 'admin');
    }

    public function store(): void
    {
        $form = $this->formFromPost();
        $errors = $this->validate($form);

        if ($errors) {
            View::render('admin/dokter/form', [
                'errors' => $errors,
                'form' => $form,
                'poli' => Poli::all(),
                'mode' => 'create',
            ], 'admin');
            return;
        }

        Dokter::create($form);
        $this->flash('ok', 'Data dokter berhasil ditambahkan.');
        $this->redirect('/admin/dokter');
    }

    public function edit($id): void
    {
        $dokter = Dokter::findById((int)$id);

        if (!$dokter) {
            $this->flash('warn', 'Data dokter tidak ditemukan.');
            $this->redirect('/admin/dokter');
        }

        View::render('admin/dokter/form', [
            'errors' => [],
            'form' => $dokter,
            'poli' => Poli::all(),
            'mode' => 'edit',
        ], 'admin');
    }

    public function update($id): void
    {
        $id = (int)$id;
        $dokter = Dokter::findById($id);

        if (!$dokter) {
            $this->flash('warn', 'Data dokter tidak ditemukan.');
            $this->redirect('/admin/dokter');
        }

        $form = $this->formFromPost();
        $errors = $this->validate($form);

        if ($errors) {
            $form['id'] = $id;
            View::render('admin/dokter/form', [
                'errors' => $errors,
                'form' => $form,
                'poli' => Poli::all(),
                'mode' => 'edit',
            ], 'admin');
            return;
        }

        Dokter::update($id, $form);
        $this->flash('ok', 'Data dokter berhasil diperbarui.');
        $this->redirect('/admin/dokter');
    }

    public function delete($id): void
    {
        try {
            Dokter::delete((int)$id);
            $this->flash('ok', 'Data dokter berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->flash('warn', 'Dokter tidak bisa dihapus karena masih terhubung dengan jadwal atau antrean.');
        }

        $this->redirect('/admin/dokter');
    }

    public function akun($id): void
    {
        $id = (int)$id;
        $dokter = Dokter::findById($id);

        if (!$dokter) {
            $this->flash('warn', 'Data dokter tidak ditemukan.');
            $this->redirect('/admin/dokter');
        }

        if (Staff::findByDoctorId($id)) {
            $this->flash('warn', 'Dokter ini sudah memiliki akun login.');
            $this->redirect('/admin/dokter');
        }

        $email = $this->bikinEmail($dokter['name']);
        $sandi = $this->sandiAcak();

        Staff::create([
            'name' => $dokter['name'],
            'email' => $email,
            'password' => $sandi,
            'plain_password' => $sandi,
            'role' => 'dokter',
            'doctors_id' => $id,
        ]);

        $this->flash('ok', 'Akun dokter dibuat.');
        $this->redirect('/admin/dokter');
    }

    private function bikinEmail(string $nama): string
    {
        $kata = preg_split('/\s+/', strtolower($nama));
        $bersih = [];
        foreach ($kata as $k) {
            $k = preg_replace('/[^a-z0-9]/', '', $k);
            if ($k !== '') $bersih[] = $k;
            if (count($bersih) >= 2) break;
        }
        $awalan = implode('.', $bersih) ?: 'dokter';
        $email = $awalan . '@medicaria.id';
        $nomor = 2;
        while (Staff::findByEmail($email)) {
            $email = $awalan . $nomor . '@medicaria.id';
            $nomor++;
        }
        return $email;
    }

    private function sandiAcak(int $panjang = 8): string
    {
        $karakter = 'abcdefghjkmnpqrstuvwxyz23456789';
        $batas = strlen($karakter) - 1;
        $sandi = '';
        for ($i = 0; $i < $panjang; $i++) {
            $sandi .= $karakter[random_int(0, $batas)];
        }
        return $sandi;
    }

    private function emptyForm(): array
    {
        return [
            'poli_id' => '',
            'name' => '',
            'specialization' => '',
            'is_active' => 1,
        ];
    }

    private function formFromPost(): array
    {
        return [
            'poli_id' => (int)($_POST['poli_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'specialization' => trim($_POST['specialization'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
    }

    private function validate(array $form): array
    {
        $errors = [];

        if ($form['poli_id'] <= 0 || !Poli::findById($form['poli_id'])) {
            $errors[] = 'Poli wajib dipilih.';
        }

        if ($form['name'] === '') {
            $errors[] = 'Nama dokter wajib diisi.';
        }

        return $errors;
    }
}
