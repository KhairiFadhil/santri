<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;
use App\Model\Jadwal;

class AntreanController
{
    public function daftar(): void
    {
        $userId = $_SESSION['user']['id'];

        if (Antrian::getAntrianAktif($userId)) {
            header('Location: /santri-belajar/public/antrean');
            exit;
        }

        $date = $_GET['schedule_date'] ?? date('Y-m-d');
        $today = date('Y-m-d');
        $maxDate = date('Y-m-d', strtotime('+3 days'));
        if ($date < $today)  $date = $today;
        if ($date > $maxDate) $date = $maxDate;

        View::render('antrean/daftar', [
            'doctors' => Jadwal::doktersOnDate($date),
            'errors'  => [],
            'form'    => ['doctor_id' => '', 'complaint' => '', 'schedule_date' => $date],
            'hari'    => Jadwal::dayName($date),
        ], 'main');
    }

    public function daftarProcess(): void
    {
        $userId = $_SESSION['user']['id'];

        if (Antrian::getAntrianAktif($userId)) {
            header('Location: /santri-belajar/public/antrean');
            exit;
        }

        $form = [
            'doctor_id'     => trim($_POST['doctor_id'] ?? ''),
            'schedule_date' => trim($_POST['schedule_date'] ?? date('Y-m-d')),
            'complaint'     => trim($_POST['complaint'] ?? ''),
        ];

        $today = date('Y-m-d');
        $maxDate = date('Y-m-d', strtotime('+3 days'));
        if ($form['schedule_date'] < $today)  $form['schedule_date'] = $today;
        if ($form['schedule_date'] > $maxDate) $form['schedule_date'] = $maxDate;

        $errors = [];
        $dokter = null;
        $jadwal = null;

        if (!$form['doctor_id']) {
            $errors[] = 'Pilih dokter.';
        } else {
            $dokter = Dokter::findById((int)$form['doctor_id']);
            if (!$dokter) {
                $errors[] = 'Dokter tidak ditemukan.';
            } else {
                $jadwal = Jadwal::find((int)$form['doctor_id'], Jadwal::dayName($form['schedule_date']));
                if (!$jadwal) {
                    $errors[] = 'Dokter tidak praktik pada tanggal tersebut.';
                } elseif ($form['schedule_date'] === date('Y-m-d') && $jadwal['time_end'] <= date('H:i:s')) {
                    $errors[] = 'Jam praktik dokter hari ini sudah berakhir. Silakan pilih tanggal lain.';
                }
            }
        }

        if ($errors) {
            View::render('antrean/daftar', [
                'doctors' => Jadwal::doktersOnDate($form['schedule_date']),
                'errors'  => $errors,
                'form'    => $form,
                'hari'    => Jadwal::dayName($form['schedule_date']),
            ], 'main');
            return;
        }

        try {
            Antrian::create([
                'user_id'        => $userId,
                'poli_id'        => (int)$dokter['poli_id'],
                'doctor_id'      => (int)$form['doctor_id'],
                'schedule_date'  => $form['schedule_date'],
                'schedule_time'  => $jadwal['time_start'],
                'complaint'      => $form['complaint'] ?: null,
                'insurance_type' => 'BPJS',
                'registered_via' => 'online',
            ]);
        } catch (\Throwable $e) {
            View::render('antrean/daftar', [
                'doctors' => Jadwal::doktersOnDate($form['schedule_date']),
                'errors'  => [$e->getMessage()],
                'form'    => $form,
                'hari'    => Jadwal::dayName($form['schedule_date']),
            ], 'main');
            return;
        }

        header('Location: /santri-belajar/public/antrean');
        exit;
    }

    public function status(): void
    {
        $userId = $_SESSION['user']['id'];
        View::render('antrean/status', [
            'antrean' => Antrian::getAntrianAktif($userId),
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
        View::render('antrean/riwayat', [
            'rows' => Antrian::RiwayatUser($userId),
        ], 'main');
    }
}
