<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;
use App\Model\Poli;
use App\Model\User;

class WalkinController extends \App\Core\Controller
{
    public function index(): void
    {
        View::render('admin/walkin/index', [
            'errors' => [],
            'form' => $this->emptyForm(),
            'doctors' => Dokter::all(true),
            'poli' => Poli::all(true),
        ], 'admin');
    }

    public function store(): void
    {
        $form = $this->formFromPost();
        $errors = $this->validate($form);
        $dokter = null;

        if (!$errors) {
            $dokter = Dokter::findById((int)$form['doctor_id']);
            if (!$dokter) {
                $errors[] = 'Dokter tidak ditemukan.';
            }
        }

        if ($errors) {
            View::render('admin/walkin/index', [
                'errors' => $errors,
                'form' => $form,
                'doctors' => Dokter::all(true),
                'poli' => Poli::all(true),
            ], 'admin');
            return;
        }

        $akun = $form['walkin_nik'] !== '' ? User::findByNik($form['walkin_nik']) : null;

        try {
            $queue = Antrian::create([
                'user_id'        => $akun['id'] ?? null,
                'walkin_name'    => $form['walkin_name'],
                'walkin_nik'     => $form['walkin_nik'] ?: null,
                'walkin_phone'   => $form['walkin_phone'] ?: null,
                'doctor_id'      => (int)$form['doctor_id'],
                'poli_id'        => (int)$dokter['poli_id'],
                'schedule_date'  => $form['schedule_date'],
                'schedule_time'  => $form['schedule_time'] ?: null,
                'complaint'      => $form['complaint'] ?: null,
                'insurance_type' => $form['insurance_type'] ?: 'Umum',
                'registered_via' => 'walkin',
                'handled_by'     => $_SESSION['staff']['id'] ?? null,
            ]);

            $this->flash('ok', 'Antrean walk-in berhasil dibuat: ' . $queue['ticket_code']);
            $this->redirect('/admin/walkin');
        } catch (\Throwable $e) {
            View::render('admin/walkin/index', [
                'errors' => [$e->getMessage()],
                'form' => $form,
                'doctors' => Dokter::all(true),
                'poli' => Poli::all(true),
            ], 'admin');
        }
    }

    private function emptyForm(): array
    {
        return [
            'walkin_name' => '',
            'walkin_nik' => '',
            'walkin_phone' => '',
            'doctor_id' => '',
            'schedule_date' => date('Y-m-d'),
            'schedule_time' => '',
            'complaint' => '',
            'insurance_type' => 'Umum',
        ];
    }

    private function formFromPost(): array
    {
        return [
            'walkin_name' => trim($_POST['walkin_name'] ?? $_POST['name'] ?? ''),
            'walkin_nik' => trim($_POST['walkin_nik'] ?? $_POST['nik'] ?? ''),
            'walkin_phone' => trim($_POST['walkin_phone'] ?? $_POST['phone'] ?? ''),
            'doctor_id' => trim($_POST['doctor_id'] ?? ''),
            'schedule_date' => trim($_POST['schedule_date'] ?? date('Y-m-d')),
            'schedule_time' => trim($_POST['schedule_time'] ?? ''),
            'complaint' => trim($_POST['complaint'] ?? ''),
            'insurance_type' => trim($_POST['insurance_type'] ?? 'Umum'),
        ];
    }

    private function validate(array $form): array
    {
        $errors = [];

        if ($form['walkin_name'] === '') {
            $errors[] = 'Nama pasien wajib diisi.';
        }

        if ($form['walkin_nik'] !== '' && !preg_match('/^\d{16}$/', $form['walkin_nik'])) {
            $errors[] = 'NIK harus 16 digit angka.';
        }

        if ((int)$form['doctor_id'] <= 0) {
            $errors[] = 'Dokter wajib dipilih.';
        }

        if ($form['schedule_date'] === '') {
            $errors[] = 'Tanggal antrean wajib diisi.';
        } elseif ($form['schedule_date'] < date('Y-m-d')
                  || $form['schedule_date'] > date('Y-m-d', strtotime('+3 days'))) {
            $errors[] = 'Tanggal antrean harus antara hari ini sampai 3 hari ke depan.';
        }

        if (!in_array($form['insurance_type'], ['BPJS', 'Asuransi', 'Umum'], true)) {
            $errors[] = 'Jenis penjamin tidak valid.';
        }

        return $errors;
    }
}
