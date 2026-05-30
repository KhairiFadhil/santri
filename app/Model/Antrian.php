<?php
namespace App\Model;

use PDO;

class Antrian
{
    public static function nextNumber(int $doctorId, string $date): int
    {
        $st = db()->prepare('SELECT COALESCE(MAX(number),0)+1 FROM queues WHERE doctor_id = ? AND schedule_date = ?');
        $st->execute([$doctorId, $date]);
        return (int)$st->fetchColumn();
    }

    // bikin tiket.
    public static function create(array $data): array
    {
        $doctorId = (int)$data['doctor_id'];
        $poliId   = (int)$data['poli_id'];
        $date     = $data['schedule_date'] ?? date('Y-m-d');
        $time     = $data['schedule_time'] ?? null;

        // 1 user 1 antrean aktif
        if (!empty($data['user_id']) && self::getAntrianAktif((int)$data['user_id'])) {
            throw new \RuntimeException('Anda sudah punya antrean aktif.');
        }

        // dokter lagi libur
        if (Jadwal::isOff($doctorId, $date)) {
            throw new \RuntimeException('Dokter sedang tidak praktik (libur) di tanggal tersebut.');
        }

        // cek kuota
        $kuota = Jadwal::sisaKuota($doctorId, $date);
        if (!$kuota['ada_jadwal']) {
            throw new \RuntimeException('Dokter tidak praktik di tanggal tersebut.');
        }
        if ($kuota['penuh']) {
            throw new \RuntimeException('Kuota dokter untuk tanggal ini sudah penuh.');
        }

        // kalau daftar hari ini, jangan sampai keburu tutup
        if (!Jadwal::masihKeburu($doctorId, $date)) {
            throw new \RuntimeException('Perkiraan tidak terlayani hari ini, jam praktik hampir habis. Silakan pilih tanggal lain.');
        }

        // prefix dari kode poli
        $poli = Poli::findById($poliId);
        if (!$poli) throw new \RuntimeException('Poli tidak ditemukan.');
        $prefix = $poli['code'];

        $sql = 'INSERT INTO queues
                  (ticket_code, number, user_id, walkin_name, walkin_nik, walkin_phone,
                   doctor_id, poli_id, schedule_date, schedule_time, complaint,
                   status, insurance_type, registered_via, handled_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        for ($try = 0; $try < 5; $try++) {
            $number = self::nextNumber($doctorId, $date);
            $ticket = $prefix . '-' . str_pad((string)$number, 3, '0', STR_PAD_LEFT);

            try {
                $st = db()->prepare($sql);
                $st->execute([
                    $ticket,
                    $number,
                    $data['user_id'] ?? null,
                    $data['walkin_name'] ?? null,
                    $data['walkin_nik'] ?? null,
                    $data['walkin_phone'] ?? null,
                    $doctorId,
                    $poliId,
                    $date,
                    $time,
                    $data['complaint'] ?? null,
                    'wait',
                    $data['insurance_type'] ?? 'Umum',
                    $data['registered_via'] ?? 'online',
                    $data['handled_by'] ?? null,
                ]);
                return self::findById((int)db()->lastInsertId());
            } catch (\PDOException $e) {
                if ($e->getCode() === '23000') continue;
                throw $e;
            }
        }
        throw new \RuntimeException('Gagal generate nomor antrean, coba lagi.');
    }

    public static function findById(int $id): ?array
    {
        $sql = 'SELECT q.*, p.name AS poli_name, p.code AS poli_code,
                       d.name AS doctor_name, u.name AS user_name
                FROM queues q
                JOIN poli p ON p.id = q.poli_id
                JOIN doctors d ON d.id = q.doctor_id
                LEFT JOIN users u ON u.id = q.user_id
                WHERE q.id = ?';
        $st = db()->prepare($sql);
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function getAntrianAktif(int $userId): ?array
    {
        $sql = "SELECT q.*, p.name AS poli_name, p.code AS poli_code, d.name AS doctor_name
                FROM queues q
                JOIN poli p ON p.id = q.poli_id
                JOIN doctors d ON d.id = q.doctor_id
                WHERE q.user_id = ? AND q.status IN ('wait','call','progress')
                ORDER BY q.created_at DESC LIMIT 1";
        $st = db()->prepare($sql);
        $st->execute([$userId]);
        return $st->fetch() ?: null;
    }

    // info posisi antrean: berapa orang di depan + siapa yang lagi dipanggil
    public static function frontStats(array $queue): array
    {
        $st = db()->prepare("SELECT COUNT(*) FROM queues
            WHERE doctor_id = ? AND schedule_date = ?
              AND number < ? AND status IN ('wait','call','progress')");
        $st->execute([$queue['doctor_id'], $queue['schedule_date'], $queue['number']]);
        $ahead = (int)$st->fetchColumn();

        $st2 = db()->prepare("SELECT ticket_code, number FROM queues
            WHERE doctor_id = ? AND schedule_date = ? AND status IN ('call','progress')
            ORDER BY status='progress' DESC, called_at DESC LIMIT 1");
        $st2->execute([$queue['doctor_id'], $queue['schedule_date']]);
        $current = $st2->fetch() ?: null;

        return [
            'ahead'          => $ahead,
            'eta_minutes'    => $ahead * (defined('QUEUE_ETA_PER_PERSON') ? QUEUE_ETA_PER_PERSON : 3),
            'calling'        => $current ? $current['ticket_code'] : null,
            'calling_number' => $current ? (int)$current['number'] : 0,
        ];
    }

    // dipakai home: semua dokter aktif hari ini + sekarang dipanggil + 3 next
    public static function liveQueue(): array
    {
        $sql = "SELECT DISTINCT q.doctor_id, d.name AS doctor_name,
                       p.name AS poli_name, p.code AS poli_code
                FROM queues q
                JOIN doctors d ON d.id = q.doctor_id
                JOIN poli p    ON p.id = q.poli_id
                WHERE q.schedule_date = CURDATE()
                ORDER BY p.name, d.name";
        $rows = db()->query($sql)->fetchAll();

        $result = [];
        foreach ($rows as $r) {
            $doctorId = (int)$r['doctor_id'];

            $st = db()->prepare("SELECT ticket_code, number, status FROM queues
                WHERE doctor_id = ? AND schedule_date = CURDATE()
                  AND status IN ('call','progress')
                ORDER BY FIELD(status,'progress','call'), called_at DESC LIMIT 1");
            $st->execute([$doctorId]);
            $now = $st->fetch() ?: null;

            $st2 = db()->prepare("SELECT ticket_code, number FROM queues
                WHERE doctor_id = ? AND schedule_date = CURDATE() AND status = 'wait'
                ORDER BY number ASC LIMIT 3");
            $st2->execute([$doctorId]);
            $next = $st2->fetchAll();

            $st3 = db()->prepare("SELECT COUNT(*) FROM queues
                WHERE doctor_id = ? AND schedule_date = CURDATE() AND status = 'wait'");
            $st3->execute([$doctorId]);
            $waiting = (int)$st3->fetchColumn();

            $result[] = [
                'doctor_id'   => $doctorId,
                'doctor_name' => $r['doctor_name'],
                'poli_name'   => $r['poli_name'],
                'poli_code'   => $r['poli_code'],
                'now_serving' => $now,
                'next'        => $next,
                'waiting'     => $waiting,
            ];
        }
        return $result;
    }

    public static function setStatus(int $id, string $status, ?int $staffId = null): bool
    {
        $valid = ['wait','call','progress','done','skip','cancel'];
        if (!in_array($status, $valid, true)) {
            throw new \InvalidArgumentException("Status tidak valid: $status");
        }

        $sets = ['status = ?', 'handled_by = ?'];
        $params = [$status, $staffId];

        if ($status === 'call')     $sets[] = 'called_at = NOW()';
        if ($status === 'progress') $sets[] = 'started_at = NOW()';
        if ($status === 'done')     $sets[] = 'completed_at = NOW()';

        $params[] = $id;
        $sql = 'UPDATE queues SET ' . implode(', ', $sets) . ' WHERE id = ?';
        return db()->prepare($sql)->execute($params);
    }

    public static function batalAntrian(int $queueId, int $userId): bool
    {
        $st = db()->prepare("UPDATE queues SET status = 'cancel'
            WHERE id = ? AND user_id = ? AND status IN ('wait','call')");
        $st->execute([$queueId, $userId]);
        return $st->rowCount() > 0;
    }

    public static function listActive(?int $poliId = null, ?string $status = null): array
    {
        $sql = "SELECT q.*, p.name AS poli_name, d.name AS doctor_name,
                       COALESCE(u.name, q.walkin_name) AS patient_name
                FROM queues q
                JOIN poli p ON p.id = q.poli_id
                JOIN doctors d ON d.id = q.doctor_id
                LEFT JOIN users u ON u.id = q.user_id
                WHERE q.status IN ('wait','call','progress')";
        $params = [];
        if ($poliId) { $sql .= ' AND q.poli_id = ?'; $params[] = $poliId; }
        if ($status) { $sql .= ' AND q.status = ?'; $params[] = $status; }
        $sql .= " ORDER BY q.schedule_date, FIELD(q.status,'call','progress','wait'), q.number ASC";
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function listHariIni(?int $poliId = null, ?string $status = null, ?int $doctorId = null): array
    {
        $sql = "SELECT q.*, p.name AS poli_name, d.name AS doctor_name,
                       COALESCE(u.name, q.walkin_name) AS patient_name
                FROM queues q
                JOIN poli p ON p.id = q.poli_id
                JOIN doctors d ON d.id = q.doctor_id
                LEFT JOIN users u ON u.id = q.user_id
                WHERE q.schedule_date = CURDATE()";
        $params = [];
        if ($poliId) { $sql .= ' AND q.poli_id = ?'; $params[] = $poliId; }
        if ($status) { $sql .= ' AND q.status = ?'; $params[] = $status; }
        if ($doctorId) { $sql .= ' AND q.doctor_id = ?'; $params[] = $doctorId; }
        $sql .= " ORDER BY FIELD(q.status,'call','progress','wait','skip','done','cancel'), q.number ASC";
        $st = db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function countByStatusHariIni(string $status): int
    {
        $st = db()->prepare("SELECT COUNT(*) FROM queues WHERE schedule_date = CURDATE() AND status = ?");
        $st->execute([$status]);
        return (int)$st->fetchColumn();
    }

    public static function jumlahAntrianHariIni(): int
    {
        return (int)db()->query("SELECT COUNT(*) FROM queues WHERE schedule_date = CURDATE()")->fetchColumn();
    }

    public static function isDoctorBusy(int $doctorId, string $date): bool
    {
        $sql = "SELECT COUNT(*) FROM queues
                WHERE doctor_id = ? AND schedule_date = ?
                  AND status IN ('call','progress')";
        $stmt = db()->prepare($sql);
        $stmt->execute([$doctorId, $date]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public static function nextWaiting(int $doctorId, string $date): ?array
    {
        $sql = "SELECT * FROM queues
                WHERE doctor_id = ? AND schedule_date = ? AND status = 'wait'
                ORDER BY number ASC LIMIT 1";
        $stmt = db()->prepare($sql);
        $stmt->execute([$doctorId, $date]);
        return $stmt->fetch() ?: null;
    }

    // tutup antrean basi: yg masih aktif tapi tanggalnya udah lewat -> skip
    public static function expireStale(): int
    {
        $st = db()->prepare("UPDATE queues SET status = 'skip'
            WHERE status IN ('wait','call','progress') AND schedule_date < CURDATE()");
        $st->execute();
        return $st->rowCount();
    }

    // panggil ulang pasien yg sempat di-skip -> balik ke wait
    public static function recall(int $id, int $doctorId): bool
    {
        $st = db()->prepare("UPDATE queues SET status = 'wait', handled_by = NULL,
                                    called_at = NULL, started_at = NULL
            WHERE id = ? AND doctor_id = ? AND status = 'skip' AND schedule_date = CURDATE()");
        $st->execute([$id, $doctorId]);
        return $st->rowCount() > 0;
    }

    public static function RiwayatUser(int $userId): array
    {
        $sql = "SELECT q.*, p.name AS poli_name, d.name AS doctor_name
                FROM queues q
                JOIN poli p ON p.id = q.poli_id
                JOIN doctors d ON d.id = q.doctor_id
                WHERE q.user_id = ? AND q.status IN ('done','cancel','skip')
                ORDER BY q.schedule_date DESC, q.created_at DESC
                LIMIT 100";
        $st = db()->prepare($sql);
        $st->execute([$userId]);
        return $st->fetchAll();
    }
}
