<?php

namespace App\Model;

class Jadwal
{
    private const DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    public static function byDokter(int $dokterId): array
    {
        $st = db()->prepare("
            SELECT * FROM schedules
            WHERE doctor_id = ?
            ORDER BY FIELD(day_of_week,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')
        ");
        $st->execute([$dokterId]);
        return $st->fetchAll();
    }

    public static function find(int $dokterId, string $hari): ?array
    {
        $st = db()->prepare('SELECT * FROM schedules WHERE doctor_id = ? AND day_of_week = ?');
        $st->execute([$dokterId, $hari]);
        return $st->fetch() ?: null;
    }

    public static function dayName(string $date): string
    {
        $ts = strtotime($date);
        return $ts ? self::DAYS[(int)date('w', $ts)] : 'Senin';
    }

    public static function availableSlots(int $dokterId, string $date, int $intervalMin = 30): array
    {
        $sched = self::find($dokterId, self::dayName($date));
        if (!$sched) return [];

        $start = strtotime($sched['time_start']);
        $end   = strtotime($sched['time_end']);
        if (!$start || !$end || $start >= $end) return [];

        $slots = [];
        for ($cur = $start; $cur < $end; $cur += $intervalMin * 60) {
            $slots[] = date('H:i', $cur);
        }
        return $slots;
    }

    // sisa kuota dokter di tanggal tertentu (kuota = capacity jadwal hari itu)
    public static function sisaKuota(int $dokterId, string $date): array
    {
        $jadwal = self::find($dokterId, self::dayName($date));
        if (!$jadwal) {
            return ['ada_jadwal' => false, 'kuota' => 0, 'terpakai' => 0, 'sisa' => 0, 'penuh' => true];
        }

        $kuota = (int)$jadwal['capacity'];
        $st = db()->prepare("SELECT COUNT(*) FROM queues
            WHERE doctor_id = ? AND schedule_date = ?
              AND status NOT IN ('cancel','skip')");
        $st->execute([$dokterId, $date]);
        $terpakai = (int)$st->fetchColumn();
        $sisa = max(0, $kuota - $terpakai);

        return [
            'ada_jadwal' => true,
            'kuota'      => $kuota,
            'terpakai'   => $terpakai,
            'sisa'       => $sisa,
            'penuh'      => $sisa <= 0,
        ];
    }

    // cek dokter libur ga di tanggal itu
    public static function isOff(int $dokterId, string $date): bool
    {
        $st = db()->prepare('SELECT 1 FROM doctor_off WHERE doctor_id = ? AND off_date = ?');
        $st->execute([$dokterId, $date]);
        return (bool)$st->fetchColumn();
    }

    public static function setOff(int $dokterId, string $date): bool
    {
        $st = db()->prepare('INSERT IGNORE INTO doctor_off (doctor_id, off_date) VALUES (?, ?)');
        return $st->execute([$dokterId, $date]);
    }

    public static function unsetOff(int $dokterId, string $date): bool
    {
        $st = db()->prepare('DELETE FROM doctor_off WHERE doctor_id = ? AND off_date = ?');
        return $st->execute([$dokterId, $date]);
    }

    // kira-kira masih keburu dilayani ga sebelum jam tutup
    public static function masihKeburu(int $dokterId, string $date): bool
    {
        // cuma ngecek kalau daftarnya buat hari ini
        if ($date !== date('Y-m-d')) return true;

        $jadwal = self::find($dokterId, self::dayName($date));
        if (!$jadwal) return false;

        // berapa orang yg masih harus dilayani
        $st = db()->prepare("SELECT COUNT(*) FROM queues
            WHERE doctor_id = ? AND schedule_date = ?
              AND status IN ('wait','call','progress')");
        $st->execute([$dokterId, $date]);
        $antri = (int)$st->fetchColumn();

        $perOrang = defined('QUEUE_ETA_PER_PERSON') ? QUEUE_ETA_PER_PERSON : 3;
        // sekarang + (antri+1) x menit per orang, masih sebelum tutup?
        $perkiraan = strtotime("+" . (($antri + 1) * $perOrang) . " minutes");
        $tutup = strtotime($date . ' ' . $jadwal['time_end']);

        return $perkiraan <= $tutup;
    }

    public static function upsert(int $dokterId, string $hari, string $start, string $end, int $capacity = 30): bool
    {
        $sql = 'INSERT INTO schedules (doctor_id, day_of_week, time_start, time_end, capacity)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    time_start = VALUES(time_start),
                    time_end   = VALUES(time_end),
                    capacity   = VALUES(capacity)';
        $st = db()->prepare($sql);
        return $st->execute([$dokterId, $hari, $start, $end, $capacity]);
    }

    public static function delete(int $dokterId, string $hari): bool
    {
        $st = db()->prepare('DELETE FROM schedules WHERE doctor_id = ? AND day_of_week = ?');
        return $st->execute([$dokterId, $hari]);
    }

    public static function doktersOnDate(string $date): array
    {
        $hari = self::dayName($date);
        $sql = "SELECT d.id, d.name, d.specialization,
                       p.id AS poli_id, p.name AS poli_name, p.code AS poli_code,
                       s.time_start, s.time_end
                FROM doctors d
                JOIN poli p ON p.id = d.poli_id
                JOIN schedules s ON s.doctor_id = d.id
                WHERE d.is_active = 1 AND s.day_of_week = ?
                  AND d.id NOT IN (SELECT doctor_id FROM doctor_off WHERE off_date = ?)";
        $params = [$hari, $date];

        if ($date === date('Y-m-d')) {
            $sql .= " AND s.time_end > ?";
            $params[] = date('H:i:s');
        }

        $sql .= " ORDER BY p.id, d.name";
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function weeklyGrid(): array
    {
        $sql = "SELECT d.id AS doctor_id, d.name, d.specialization,
                       p.name AS poli_name,
                       s.day_of_week, s.time_start, s.time_end
                FROM doctors d
                JOIN poli p ON p.id = d.poli_id
                LEFT JOIN schedules s ON s.doctor_id = d.id
                WHERE d.is_active = 1
                ORDER BY p.id, d.name";
        $rows = db()->query($sql)->fetchAll();

        $grid = [];
        foreach ($rows as $r) {
            $key = $r['doctor_id'];
            if (!isset($grid[$key])) {
                $grid[$key] = [
                    'doctor_id' => $r['doctor_id'],
                    'name'      => $r['name'],
                    'spec'      => $r['specialization'],
                    'poli_name' => $r['poli_name'],
                    'blocks'    => array_fill_keys(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'], null),
                ];
            }
            if ($r['day_of_week']) {
                $grid[$key]['blocks'][$r['day_of_week']] =
                    substr($r['time_start'], 0, 5) . '–' . substr($r['time_end'], 0, 5);
            }
        }
        return array_values($grid);
    }
}
