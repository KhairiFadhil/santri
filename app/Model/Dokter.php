<?php
namespace App\Model;                                                                                                                       

use PDO;
class Dokter {
      public static function all(bool $isAktif = false): array
      {
        $sql = 'SELECT d.*, p.name AS poli_name, p.code AS poli_code
                  FROM doctors d JOIN poli p ON p.id = d.poli_id';
        if ($isAktif) $sql .= ' WHERE d.is_active = 1';
        $sql .= ' ORDER BY p.id, d.name';
        return db()->query($sql)->fetchAll();
      }

      public static function findById(int $id): ?array
      {
        $sql = 'SELECT d.*, p.name AS poli_name, p.code AS poli_code
                  FROM doctors d JOIN poli p ON p.id = d.poli_id
                  WHERE d.id = ?';
        $st = db()->prepare($sql);
        $st->execute([$id]);
        $r = $st->fetch();
        return $r ?: null;
      }

      public static function findByPoli(int $poliId): array
      {
        $sql = 'SELECT * FROM doctors WHERE poli_id = ? AND is_active = 1 ORDER BY name';
        $st = db()->prepare($sql);
        $st->execute([$poliId]);
        return $st->fetchAll();
      }

      public static function create(array $data): int
      {
        $sql = 'INSERT INTO doctors (poli_id, name, specialization, photo, is_active)
                  VALUES (?, ?, ?, ?, ?)';
        $st = db()->prepare($sql);
        $st->execute([
            (int)$data['poli_id'],
            $data['name'],
            $data['specialization'] ?? null,
            $data['photo'] ?? null,
            !empty($data['is_active']) ? 1 : 0,
        ]);
        return (int)db()->lastInsertId();
      }

      public static function update(int $id, array $data): bool                                                                                        
      {
        $sql = '
            UPDATE doctors
            SET poli_id = ?, name = ?, specialization = ?, is_active = ?
            WHERE id = ?
        ';
        $st = db()->prepare($sql);
        return $st->execute([
            (int)$data['poli_id'],
            $data['name'],
            $data['specialization'] ?? null,
            !empty($data['is_active']) ? 1 : 0,
            $id,
        ]);
      }

      public static function delete(int $id): bool
      {
        $st = db()->prepare('DELETE FROM doctors WHERE id = ?');
        return $st->execute([$id]);
      }

      public static function count(): int
      {
        return (int)db()->query('SELECT COUNT(*) FROM doctors WHERE is_active = 1')->fetchColumn();
      }
  }

?>