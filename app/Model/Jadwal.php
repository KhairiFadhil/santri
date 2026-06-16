<?php

namespace App\Model;

class Jadwal
{
    private const DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    public static function find(int $dokterId, string $hari): ?array
    {
        $st = db()->prepare('SELECT * FROM schedules WHERE doctor_id = ? AND day_of_week = ?');
        $st->execute([$dokterId, $hari]);
        return $st->fetch() ?: null;
    }

    public static function sedangLibur(int $dokterId, string $tanggal){
        $st = db()->prepare('SELECT 1 FROM doctor_off WHERE doctor_id = ? AND off_date = ?');
        $st->execute([$dokterId, $tanggal]);
        return (bool)$st->fetchColumn();
    }
    public static function setOff(int $dokterId, string $tanggal): bool
    {
        $st = db()->prepare('INSERT IGNORE INTO doctor_off (doctor_id, off_date) VALUES (?, ?)');
        return $st->execute([$dokterId, $tanggal]);
    }
    public static function namaHari($tanggal): string
    {
        return self::DAYS[(int)date('w', strtotime($tanggal))];

    }
    public static function unsetOff(int $dokterId, string $tanggal): bool
    {
        $st = db()->prepare('DELETE FROM doctor_off WHERE doctor_id = ? AND off_date = ?');
        return $st->execute([$dokterId, $tanggal]);
    }

    public static function sisaKuota(int $dokterId, string $tanggal): array
    {
        $jadwal = self::find($dokterId, self::namaHari($tanggal));
        if (!$jadwal) {
            return ['ada_jadwal' => false, 'kuota' => 0, 'terpakai' => 0, 'sisa' => 0, 'penuh' => true];
        }
        $kuota = (int)$jadwal['capacity'];
        $st = db()->prepare("SELECT COUNT(*) FROM queues WHERE doctor_id = ? AND schedule_date = ? AND status NOT IN ('cancel','skip')");
        $st->execute([$dokterId, $tanggal]);
        $terpakai = $st->fetchColumn();
        $sisa = max(0, $kuota - $terpakai);
        return [
            "ada_jadwal" => true,
            "kuota" => $kuota,
            "terpakai" => $terpakai,
            "sisa" => max(0, $kuota - $terpakai),
            "penuh" => $sisa <= 0
        ];

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

    public static function masihKeburu(int $dokterId, string $tanggal): bool
    {
        if ($tanggal !== date('Y-m-d')) return true;

        $jadwal = self::find($dokterId, self::namaHari($tanggal));
        if (!$jadwal) return false;

        $st = db()->prepare("SELECT COUNT(*) FROM queues
            WHERE doctor_id = ? AND schedule_date = ?
              AND status IN ('wait','call','progress')");
        $st->execute([$dokterId, $tanggal]);
        $antri = (int)$st->fetchColumn();

        $perOrang = defined('ANTRIAN_PERORANG') ? ANTRIAN_PERORANG : 3;
        $perkiraan = strtotime("+" . (($antri + 1) * $perOrang) . " minutes");
        $tutup = strtotime($tanggal . ' ' . $jadwal['time_end']);

        return $perkiraan <= $tutup;
    }

    public static function dokterPraktik(string $tanggal): array
    {
        $hari = self::namaHari($tanggal);
        $sql = "SELECT d.id, d.name, d.specialization,
                       p.id AS poli_id, p.name AS poli_name, p.code AS poli_code,
                       s.time_start, s.time_end
                FROM doctors d
                JOIN poli p ON p.id = d.poli_id
                JOIN schedules s ON s.doctor_id = d.id
                WHERE d.is_active = 1 AND s.day_of_week = ?
                  AND d.id NOT IN (SELECT doctor_id FROM doctor_off WHERE off_date = ?)";
        $params = [$hari, $tanggal];

        if ($tanggal === date('Y-m-d')) {
            $sql .= " AND s.time_end > ?";
            $params[] = date('H:i:s');
        }

        $sql .= " ORDER BY p.id, d.name";
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }
}
