<?php

namespace App\Controllers\Dokter;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Dokter;
use App\Model\Jadwal;

class LoketController
{
    private const BASE = '/santri-belajar/public';

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
            'dokter'  => $infoDokter,
            'now'     => $now,
            'waiting' => $waiting,
            'selesai' => $selesai,
            'isBusy'  => Antrian::isDoctorBusy($doctorId, date('Y-m-d')),
            'isOff'   => Jadwal::isOff($doctorId, date('Y-m-d')),
        ], 'main');
    }

    // dokter libur dadakan, tutup praktik hari ini
    public function tutup(): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        Jadwal::setOff($doctorId, date('Y-m-d'));
        $this->flash('success', 'Praktik hari ini ditutup. Pasien tidak bisa daftar lagi.');
        $this->redirect('/dokter/loket');
    }

    // buka lagi praktik hari ini
    public function buka(): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        Jadwal::unsetOff($doctorId, date('Y-m-d'));
        $this->flash('success', 'Praktik hari ini dibuka kembali.');
        $this->redirect('/dokter/loket');
    }

    // POST /dokter/loket/{id}/call - panggil pasien wait -> call
    public function call($id): void
    {
        $doctorId = $_SESSION['staff']['doctors_id'] ?? null;
        $staffId = $_SESSION['staff']['id'] ?? null;

        if(Antrian::isDoctorBusy((int)$doctorId, date('Y-m-d'))) {
            $this->flash('error', 'Selesaikan dulu pasien sekarang.');
            $this->redirect('/dokter/loket');
            return;
        }
        
        Antrian::setStatus((int)$id,'call', $staffId);
        $this->flash('success', 'Pasien dipanggil.');
        $this->redirect('/dokter/loket');
    }

    public function progress($id): void
    {
        $staffId = (int)($_SESSION['staff']['id'] ?? 0);
        Antrian::setStatus((int)$id, 'progress', $staffId);
        $this->flash('success', 'Mulai periksa.');
        $this->redirect('/dokter/loket');
    }

    public function done($id): void
    {
        $staffId = (int)($_SESSION['staff']['id'] ?? 0);
        Antrian::setStatus((int)$id, 'done', $staffId);
        $this->flash('success', 'Pasien selesai.');
        $this->redirect('/dokter/loket');
    }

    public function skip($id): void
    {
        $staffId = (int)($_SESSION['staff']['id'] ?? 0);
        Antrian::setStatus((int)$id, 'skip', $staffId);
        $this->flash('success', 'Pasien dilewati.');
        $this->redirect('/dokter/loket');
    }

    public function doneAndNext($id): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        $staffId  = (int)($_SESSION['staff']['id'] ?? 0);

        Antrian::setStatus((int)$id, 'done', $staffId);

        $next = Antrian::nextWaiting($doctorId, date('Y-m-d'));
        if ($next) {
            Antrian::setStatus((int)$next['id'], 'call', $staffId);
            $this->flash('success', 'Selesai. Memanggil ' . $next['ticket_code'] . '.');
        } else {
            $this->flash('success', 'Pasien selesai. Tidak ada antrean lagi.');
        }
        $this->redirect('/dokter/loket');
    }

    // panggil ulang pasien yg sempat di-skip
    public function recall($id): void
    {
        $doctorId = (int)($_SESSION['staff']['doctors_id'] ?? 0);
        $ok = Antrian::recall((int)$id, $doctorId);
        $this->flash($ok ? 'success' : 'warn', $ok ? 'Pasien dikembalikan ke antrean.' : 'Gagal panggil ulang.');
        $this->redirect('/dokter/loket');
    }

    private function flash(string $kind, string $message): void
    {
        $_SESSION['flash'] = compact('kind', 'message');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . self::BASE . $path);
        exit;
    }
}
