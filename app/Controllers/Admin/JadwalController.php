<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Dokter;
use App\Model\Jadwal;
use App\Model\Antrian;

class JadwalController extends \App\Core\Controller
{
    private const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(): void
    {
        $cutiByDokter = [];
        foreach (Jadwal::cutiMendatang() as $c) {
            $cutiByDokter[(int)$c['doctor_id']][] = $c['off_date'];
        }

        View::render('admin/jadwal/index', [
            'grid' => $this->weeklyGrid(),
            'doctors' => Dokter::all(true),
            'days' => self::DAYS,
            'cutiByDokter' => $cutiByDokter,
        ], 'admin');
    }

    public function setCuti(): void
    {
        $doctorId = (int)($_POST['doctor_id'] ?? 0);
        $tanggal = trim($_POST['off_date'] ?? '');

        if ($doctorId <= 0 || !Dokter::findById($doctorId) || $tanggal === '' || $tanggal < date('Y-m-d')) {
            $this->flash('warn', 'Dokter dan tanggal cuti (minimal hari ini) wajib diisi.');
            $this->redirect('/admin/jadwal');
        }

        Jadwal::setOff($doctorId, $tanggal);
        $dibatalkan = Antrian::batalSemuaMenunggu($doctorId, $tanggal);

        $this->flash('ok', "Cuti dokter ditetapkan. $dibatalkan antrean menunggu di tanggal itu dibatalkan.");
        $this->redirect('/admin/jadwal');
    }

    public function batalCuti(): void
    {
        $doctorId = (int)($_POST['doctor_id'] ?? 0);
        $tanggal = trim($_POST['off_date'] ?? '');

        if ($doctorId > 0 && $tanggal !== '') {
            Jadwal::unsetOff($doctorId, $tanggal);
        }

        $this->flash('ok', 'Cuti dokter dibatalkan.');
        $this->redirect('/admin/jadwal');
    }

    public function upsert(): void
    {
        $doctorId = (int)($_POST['doctor_id'] ?? 0);
        $hari = trim($_POST['day_of_week'] ?? $_POST['hari'] ?? '');
        $mulai = trim($_POST['time_start'] ?? $_POST['start'] ?? '');
        $selesai = trim($_POST['time_end'] ?? $_POST['end'] ?? '');
        $capacity = (int)($_POST['capacity'] ?? 30);

        $errors = [];

        if ($doctorId <= 0 || !Dokter::findById($doctorId)) {
            $errors[] = 'Dokter wajib dipilih.';
        }

        if (!in_array($hari, self::DAYS, true)) {
            $errors[] = 'Hari tidak valid.';
        }

        if (!$this->validTime($mulai) || !$this->validTime($selesai)) {
            $errors[] = 'Jam mulai dan selesai wajib format HH:MM.';
        }

        if ($this->validTime($mulai) && $this->validTime($selesai) && $mulai >= $selesai) {
            $errors[] = 'Jam selesai harus lebih besar dari jam mulai.';
        }

        if ($capacity < 1) {
            $errors[] = 'Kapasitas minimal 1.';
        }

        if ($errors) {
            $this->flash('warn', implode(' ', $errors));
            $this->redirect('/admin/jadwal');
        }

        Jadwal::upsert($doctorId, $hari, $mulai, $selesai, $capacity);

        $this->flash('ok', 'Jadwal dokter berhasil disimpan.');
        $this->redirect('/admin/jadwal');
    }

    public function delete(): void
    {
        $doctorId = (int)($_POST['doctor_id'] ?? 0);
        $hari = trim($_POST['day_of_week'] ?? $_POST['hari'] ?? '');

        if ($doctorId <= 0 || !in_array($hari, self::DAYS, true)) {
            $this->flash('warn', 'Data jadwal tidak valid.');
            $this->redirect('/admin/jadwal');
        }

        Jadwal::delete($doctorId, $hari);

        $this->flash('ok', 'Jadwal dokter berhasil dihapus.');
        $this->redirect('/admin/jadwal');
    }

    private function weeklyGrid(): array
    {
        $sql = "SELECT d.id AS doctor_id, d.name, d.specialization, p.name AS poli_name,
                       s.day_of_week, s.time_start, s.time_end, s.capacity
                FROM doctors d
                JOIN poli p ON p.id = d.poli_id
                LEFT JOIN schedules s ON s.doctor_id = d.id
                WHERE d.is_active = 1
                ORDER BY p.id, d.name";

        $rows = db()->query($sql)->fetchAll();
        $grid = [];

        foreach ($rows as $row) {
            $key = (int)$row['doctor_id'];

            if (!isset($grid[$key])) {
                $grid[$key] = [
                    'doctor_id' => $key,
                    'name' => $row['name'],
                    'specialization' => $row['specialization'],
                    'poli_name' => $row['poli_name'],
                    'blocks' => array_fill_keys(self::DAYS, null),
                ];
            }

            if (!empty($row['day_of_week'])) {
                $grid[$key]['blocks'][$row['day_of_week']] = [
                    'time_start' => substr($row['time_start'], 0, 5),
                    'time_end' => substr($row['time_end'], 0, 5),
                    'capacity' => (int)$row['capacity'],
                    'label' => substr($row['time_start'], 0, 5) . '–' . substr($row['time_end'], 0, 5),
                ];
            }
        }

        return array_values($grid);
    }

    private function validTime($time)
    {
        $bagian = explode(':', $time);
        if (count($bagian) !== 2) return false;
        if (!ctype_digit($bagian[0]) || !ctype_digit($bagian[1])) return false;

        $jam = (int)$bagian[0];
        $menit = (int)$bagian[1];
        return $jam >= 0 && $jam <= 23 && $menit >= 0 && $menit <= 59;
    }
}
