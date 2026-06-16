<?php
namespace App\Controllers;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;
use App\Model\Jadwal;

class AntreanController extends \App\Core\Controller
{
    public function daftar(): void
    {
        $userId = $_SESSION['user']['id'];

        if (Antrian::antrianAktif($userId)) {
            $this->redirect('/antrean');
        }

        $tgl = $_GET['schedule_date'] ?? date('Y-m-d');
        $hariIni = date('Y-m-d');
        $tglMax = date('Y-m-d', strtotime('+3 days'));
        if ($tgl < $hariIni) $tgl = $hariIni;
        if ($tgl > $tglMax)  $tgl = $tglMax;

        View::render('antrean/daftar', [
            'doctors' => $this->dokterKuota($tgl),
            'errors'  => [],
            'form'    => ['doctor_id' => '', 'complaint' => '', 'schedule_date' => $tgl],
            'hari'    => Jadwal::namaHari($tgl),
        ], 'main');
    }

    private function dokterKuota($tgl)
    {
        $listDok = Jadwal::dokterPraktik($tgl);
        foreach ($listDok as $i => $d) {
            $kuota = Jadwal::sisaKuota((int)$d['id'], $tgl);
            $listDok[$i]['sisa'] = $kuota['sisa'];
            $listDok[$i]['penuh'] = $kuota['penuh'];
        }
        return $listDok;
    }

    public function prosesDaftar(): void
    {
        $userId = $_SESSION['user']['id'];

        if (Antrian::antrianAktif($userId)) {
            $this->redirect('/antrean');
        }

        $form = [
            'doctor_id'     => trim($_POST['doctor_id'] ?? ''),
            'schedule_date' => trim($_POST['schedule_date'] ?? date('Y-m-d')),
            'complaint'     => trim($_POST['complaint'] ?? ''),
        ];

        $hariIni = date('Y-m-d');
        $tglMax = date('Y-m-d', strtotime('+3 days'));
        if ($form['schedule_date'] < $hariIni) $form['schedule_date'] = $hariIni;
        if ($form['schedule_date'] > $tglMax)  $form['schedule_date'] = $tglMax;

        $errors = [];
        $dokter = null;
        $jadwal = null;

        if (!$form['doctor_id']) {
            $errors[] = 'Pilih dokter.';
        } else {
            $dokter = Dokter::findById((int)$form['doctor_id']);
            if (!$dokter || !$dokter['is_active']) {
                $errors[] = 'Dokter tidak tersedia.';
            } else {
                $jadwal = Jadwal::find((int)$form['doctor_id'], Jadwal::namaHari($form['schedule_date']));
                if (!$jadwal) {
                    $errors[] = 'Dokter tidak praktik pada tanggal tersebut.';
                } elseif ($form['schedule_date'] === date('Y-m-d') && $jadwal['time_end'] <= date('H:i:s')) {
                    $errors[] = 'Jam praktik dokter hari ini sudah berakhir. Silakan pilih tanggal lain.';
                }
            }
        }

        if ($errors) {
            View::render('antrean/daftar', [
                'doctors' => $this->dokterKuota($form['schedule_date']),
                'errors'  => $errors,
                'form'    => $form,
                'hari'    => Jadwal::namaHari($form['schedule_date']),
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
                'doctors' => $this->dokterKuota($form['schedule_date']),
                'errors'  => [$e->getMessage()],
                'form'    => $form,
                'hari'    => Jadwal::namaHari($form['schedule_date']),
            ], 'main');
            return;
        }

        $this->redirect('/antrean');
    }

    public function statusAntri(): void
    {
        $userId = $_SESSION['user']['id'];
        View::render('antrean/status', [
            "antrean" => Antrian::antrianAktif($userId)
        ], 'main');
    }

    public function batal(): void{
        $userId = $_SESSION['user']['id'];
        $idAntri = (int)($_POST['queue_id'] ?? 0);
        if($idAntri > 0){
            Antrian::batalAntrian($idAntri ,$userId);
            }
         $this->redirect('/dashboard');

    }

    public function riwayat():void
    {
        $userId = $_SESSION['user']['id'];
        View::render('antrean/riwayat',[
            'rows' => Antrian::RiwayatUser($userId)
        ], 'main');
    }
}
