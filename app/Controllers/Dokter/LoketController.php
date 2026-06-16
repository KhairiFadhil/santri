<?php

namespace App\Controllers\Dokter;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;
use App\Model\Jadwal;

class LoketController extends \App\Core\Controller
{
    public function index(): void
    {
        $doctorId   = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        $infoDokter = Dokter::findById($doctorId);

        if (!$infoDokter) {
            die('Akun ini belum di-link ke record dokter.');
        }

        $antrianToday = Antrian::listHariIni($infoDokter['poli_id'], null, $doctorId);

        $now     = null;
        $waiting = [];
        $selesai = [];

        foreach ($antrianToday as $a) {
            if (in_array($a['status'], ['call','progress'], true)) {
                if ($now === null) $now = $a;
            } elseif ($a['status'] === 'wait') {
                $waiting[] = $a;
            } else {
                $selesai[] = $a;
            }
        }

        View::render('dokter/loket/index', [
            'title'   => 'Loket Dokter — SANTRI',
            'dokter'  => $infoDokter,
            'now'     => $now,
            'waiting' => $waiting,
            'selesai' => $selesai,
            'isBusy'  => Antrian::dokterSibuk($doctorId, date('Y-m-d')),
            'isOff'   => Jadwal::sedangLibur($doctorId, date('Y-m-d')),
        ]);
    }


    public function tutup(): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        Jadwal::setOff($doctorId, date('Y-m-d'));
        $this->flash('success', 'Praktik hari ini ditutup. Pasien tidak bisa daftar lagi.');
        $this->redirect('/dokter/loket');
    }


    public function buka(): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        Jadwal::unsetOff($doctorId, date('Y-m-d'));
        $this->flash('success', 'Praktik hari ini dibuka kembali.');
        $this->redirect('/dokter/loket');
    }

    public function call($id): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        $staffId  = (int)($_SESSION['staff']['id'] ?? 0);

        if (Antrian::dokterSibuk($doctorId, date('Y-m-d'))) {
            $this->flash('error', 'Selesaikan dulu pasien sekarang.');
            $this->redirect('/dokter/loket');
            return;
        }
        
        if (!Antrian::ubahStatus((int)$id, 'call', (int)$doctorId, (int)$staffId)) {
            $this->flash('error', 'Antrean tidak ditemukan atau bukan milik Anda.');
            $this->redirect('/dokter/loket');
            return;
        }
        $this->flash('success', 'Pasien dipanggil.');
        $this->redirect('/dokter/loket');
    }

    public function progress($id): void
    {
        $staffId  = (int)($_SESSION['staff']['id'] ?? 0);
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        if (!Antrian::ubahStatus((int)$id, 'progress', $doctorId, $staffId)) {
            $this->flash('error', 'Antrean bukan milik Anda.');
            $this->redirect('/dokter/loket');
            return;
        }
        $this->flash('success', 'Mulai periksa.');
        $this->redirect('/dokter/loket');
    }

    public function done($id): void
    {
        $staffId  = (int)($_SESSION['staff']['id'] ?? 0);
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        if (!Antrian::ubahStatus((int)$id, 'done', $doctorId, $staffId)) {
            $this->flash('error', 'Antrean bukan milik Anda.');
            $this->redirect('/dokter/loket');
            return;
        }
        $this->flash('success', 'Pasien selesai.');
        $this->redirect('/dokter/loket');
    }

    public function skip($id): void
    {
        $staffId  = (int)($_SESSION['staff']['id'] ?? 0);
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        if (!Antrian::ubahStatus((int)$id, 'skip', $doctorId, $staffId)) {
            $this->flash('error', 'Antrean bukan milik Anda.');
            $this->redirect('/dokter/loket');
            return;
        }
        $this->flash('success', 'Pasien dilewati.');
        $this->redirect('/dokter/loket');
    }

    public function doneAndNext($id): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        $staffId  = (int)($_SESSION['staff']['id'] ?? 0);

        if (!Antrian::ubahStatus((int)$id, 'done', $doctorId, $staffId)) {
            $this->flash('error', 'Antrean bukan milik Anda.');
            $this->redirect('/dokter/loket');
            return;
        }

        $next = Antrian::antrianBerikutnya($doctorId, date('Y-m-d'));
        if ($next) {
            Antrian::ubahStatus((int)$next['id'], 'call', $doctorId, $staffId);
            $this->flash('success', 'Selesai. Memanggil ' . $next['ticket_code'] . '.');
        } else {
            $this->flash('success', 'Pasien selesai. Tidak ada antrean lagi.');
        }
        $this->redirect('/dokter/loket');
    }


    public function recall($id): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        $ok = Antrian::recall((int)$id, $doctorId);
        $this->flash($ok ? 'success' : 'warn', $ok ? 'Pasien dikembalikan ke antrean.' : 'Gagal panggil ulang.');
        $this->redirect('/dokter/loket');
    }
}
