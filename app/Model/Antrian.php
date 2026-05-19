<?php
namespace App\Model;                                                                                                                       

use PDO;
class Antrian {
      public static function generateNumber(int $poliId, string $date): array
      {
          $pdo = db();
  
          $poli = Poli::findById($poliId);
          if (!$poli) throw new RuntimeException('Poli tidak ditemukan.');
          $prefix = $poli['code'];
  
          $pdo->beginTransaction();
          try {
              $st = $pdo->prepare('
                  SELECT last_number FROM queue_counters
                  WHERE poli_id = ? AND counter_date = ?
                  FOR UPDATE
              ');
              $st->execute([$poliId, $date]);
              $row = $st->fetch();
  
              if ($row === false) {
                  $pdo->prepare('
                      INSERT INTO queue_counters (poli_id, counter_date, last_number)
                      VALUES (?, ?, 0)
                  ')->execute([$poliId, $date]);
                  $last = 0;
              } else {
                  $last = (int)$row['last_number'];
              }
  
              $next = $last + 1;
  
              $pdo->prepare('
                  UPDATE queue_counters SET last_number = ?
                  WHERE poli_id = ? AND counter_date = ?
              ')->execute([$next, $poliId, $date]);
  
              $pdo->commit();
          } catch (Throwable $e) {
              $pdo->rollBack();
              throw $e;
          }
  
          return [
              'number'      => $next,
              'prefix'      => $prefix,
              'ticket_code' => $prefix . '-' . str_pad((string)$next, 3, '0', STR_PAD_LEFT),
          ];
      }
  
      public static function create(array $data): array
      {
          $poliId   = (int)$data['poli_id'];
          $doctorId = (int)$data['doctor_id'];
          $date     = $data['schedule_date'] ?? date('Y-m-d');
          $time     = $data['schedule_time'] ?? null;
          if (!empty($data['user_id'])) {
              if (self::getAntrianAktif((int)$data['user_id'])) {
                  throw new RuntimeException('Anda sudah punya antrean aktif.');
              }
          }
          $gen = self::generateNumber($poliId, $date);
  
          $sql = 'INSERT INTO queues
                    (ticket_code, prefix, number, user_id, walkin_name, walkin_nik, walkin_phone,
                     doctor_id, poli_id, schedule_date, schedule_time, complaint,
                     status, insurance_type, registered_via)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
  
          $st = db()->prepare($sql);
          $st->execute([
              $gen['ticket_code'],
              $gen['prefix'],
              $gen['number'],
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
          ]);
  
          return self::findById((int)db()->lastInsertId());
      }
  
      public static function findById(int $id): ?array
      {
          $sql = 'SELECT q.*, p.name AS poli_name, p.code AS poli_code,
                         d.name AS doctor_name,
                         u.name AS user_name
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
          $sql = "SELECT q.*, p.name AS poli_name, d.name AS doctor_name
                  FROM queues q
                  JOIN poli p ON p.id = q.poli_id
                  JOIN doctors d ON d.id = q.doctor_id
                  WHERE q.user_id = ? AND q.status IN ('wait','call','progress')
                  ORDER BY q.created_at DESC LIMIT 1";
          $st = db()->prepare($sql);
          $st->execute([$userId]);
          return $st->fetch() ?: null;
      }
  
      public static function setStatus(int $id, string $status, ?int $staffId = null): bool
      {
          $valid = ['wait','call','progress','done','skip','cancel'];
          if (!in_array($status, $valid, true)) {
              throw new InvalidArgumentException("Status tidak valid: $status");
          }
  
          $sets = ['status = ?', 'handled_by = ?'];
          $params = [$status, $staffId];
  
          if ($status === 'call')     $sets[] = 'called_at = NOW()';
          if ($status === 'progress') $sets[] = 'started_at = NOW()';
          if ($status === 'done')     $sets[] = 'completed_at = NOW()';
  
          $params[] = $id;
          $sql = 'UPDATE queues SET ' . implode(', ', $sets) . ' WHERE id = ?';
          $st = db()->prepare($sql);
          return $st->execute($params);
      }
  
      public static function batalAntrian(int $queueId, int $userId): bool
      {
          $st = db()->prepare("
              UPDATE queues SET status = 'cancel'
              WHERE id = ? AND user_id = ? AND status IN ('wait','call')
          ");
          $st->execute([$queueId, $userId]);
          return $st->rowCount() > 0;
      }
  
      public static function listHariIni(?int $poliId = null, ?string $status = null): array
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
?>