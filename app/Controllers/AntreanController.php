<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;

class AntreanController
{
    public function daftar(): void
    {
        $userId = $_SESSION['user']['id'];
        $aktif  = Antrian::getAntrianAktif($userId);

        if ($aktif) {
            header('Location: /santri-belajar/public/antrean');
            exit;
        }

        View::render('antrean/daftar', [
            'doctors' => Dokter::all(true),
            'errors'  => [],
            'form'    => $this->emptyForm(),
        ], 'main');
    }

    public function daftarProcess(): void
    {
        $userId = $_SESSION['user']['id'];

        if (Antrian::getAntrianAktif($userId)) {
            header('Location: /santri-belajar/public/antrean');
            exit;
        }

        $form = $this->emptyForm();
        foreach ($form as $key => $_) {
            $form[$key] = trim($_POST[$key] ?? '');
        }

        $errors = [];
        if (!$form['doctor_id'])     $errors[] = 'Pilih dokter.';
        if (!$form['schedule_date']) $errors[] = 'Pilih tanggal.';
        if (!$form['schedule_time']) $errors[] = 'Pilih jam.';

        if (!$errors) {
            $dokter = Dokter::findById((int)$form['doctor_id']);
            if (!$dokter) {
                $errors[] = 'Dokter tidak ditemukan.';
            }
        }

        if ($errors) {
            View::render('antrean/daftar', [
                'doctors' => Dokter::all(true),
                'errors'  => $errors,
                'form'    => $form,
            ], 'main');
            return;
        }

        try {
            Antrian::create([
                'user_id'        => $userId,
                'poli_id'        => (int)$dokter['poli_id'],
                'doctor_id'      => (int)$form['doctor_id'],
                'schedule_date'  => $form['schedule_date'],
                'schedule_time'  => $form['schedule_time'],
                'complaint'      => $form['complaint'] ?: null,
                'insurance_type' => 'BPJS',
                'registered_via' => 'online',
            ]);
        } catch (\Throwable $e) {
            View::render('antrean/daftar', [
                'doctors' => Dokter::all(true),
                'errors'  => [$e->getMessage()],
                'form'    => $form,
            ], 'main');
            return;
        }

        header('Location: /santri-belajar/public/antrean');
        exit;
    }

    public function status(): void
    {
        $userId = $_SESSION['user']['id'];
        $aktif  = Antrian::getAntrianAktif($userId);

        View::render('antrean/status', [
            'antrean' => $aktif,
        ], 'main');
    }

    public function cancel(): void
    {
        $userId  = $_SESSION['user']['id'];
        $queueId = (int)($_POST['queue_id'] ?? 0);

        if ($queueId) {
            Antrian::batalAntrian($queueId, $userId);
        }

        header('Location: /santri-belajar/public/dashboard');
        exit;
    }

    public function riwayat(): void
    {
        $userId = $_SESSION['user']['id'];
        $rows   = Antrian::RiwayatUser($userId);

        View::render('antrean/riwayat', [
            'rows' => $rows,
        ], 'main');
    }

    private function emptyForm(): array
    {
        return [
            'doctor_id'     => '',
            'schedule_date' => date('Y-m-d'),
            'schedule_time' => '',
            'complaint'     => '',
        ];
    }
}
