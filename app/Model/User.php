<?php

namespace App\Model;                                                                                                                       

use PDO;

class User
{
      public static function all(int $limit = 100): array
      {
          $st = db()->prepare('SELECT * FROM users ORDER BY created_at DESC LIMIT ?');
          $st->bindValue(1, $limit, PDO::PARAM_INT);
          $st->execute();
          return $st->fetchAll();
      }

      public static function findById(int $id): ?array
      {
          $st = db()->prepare('SELECT * FROM users WHERE id = ?');
          $st->execute([$id]);
          return $st->fetch() ?: null;
      }

      public static function findByEmail(string $email): ?array
      {
          $st = db()->prepare('SELECT * FROM users WHERE email = ?');
          $st->execute([$email]);
          return $st->fetch() ?: null;
      }

      public static function findByNik(string $nik): ?array
      {
          $st = db()->prepare('SELECT * FROM users WHERE nik = ?');
          $st->execute([$nik]);
          return $st->fetch() ?: null;
      }

      public static function existsByEmail(string $email, ?int $exceptId = null): bool
      {
          $sql = 'SELECT COUNT(*) FROM users WHERE email = ?';
          $params = [$email];
          if ($exceptId !== null) {
              $sql .= ' AND id <> ?';
              $params[] = $exceptId;
          }
          $st = db()->prepare($sql);
          $st->execute($params);
          return (int)$st->fetchColumn() > 0;
      }

      public static function existsByNik(string $nik, ?int $exceptId = null): bool
      {
          $sql = 'SELECT COUNT(*) FROM users WHERE nik = ?';
          $params = [$nik];
          if ($exceptId !== null) {
              $sql .= ' AND id <> ?';
              $params[] = $exceptId;
          }
          $st = db()->prepare($sql);
          $st->execute($params);
          return (int)$st->fetchColumn() > 0;
      }

      public static function create(array $data): int
      {
          $hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
          $sql = 'INSERT INTO users
                  (nik, name, email, password_hash, phone, birth, gender,
                   address, insurance_type, insurance_no)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
          $st = db()->prepare($sql);
          $st->execute([
              $data['nik'],
              $data['name'],
              $data['email'],
              $hash,
              $data['phone'] ?? null,
              $data['birth'] ?? null,
              $data['gender'] ?? null,
              $data['address'] ?? null,
              $data['insurance_type'] ?? 'Umum',
              $data['insurance_no'] ?? null,
          ]);
          return (int)db()->lastInsertId();
      }

      public static function update(int $id, array $data): bool
      {
          $allowed = ['name','phone','birth','gender','address',
                      'insurance_type','insurance_no','email','nik'];
          $sets = [];
          $params = [];
          foreach ($allowed as $col) {
              if (array_key_exists($col, $data)) {
                  $sets[] = "$col = ?";
                  $params[] = $data[$col];
              }
          }
          if (empty($sets)) return false;
          $params[] = $id;
          $st = db()->prepare('UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = ?');
          return $st->execute($params);
      }

      public static function updatePassword(int $id, string $newPassword): bool
      {
          $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
          $st = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
          return $st->execute([$hash, $id]);
      }

      public static function delete(int $id): bool
      {
          $st = db()->prepare('DELETE FROM users WHERE id = ?');
          return $st->execute([$id]);
      }

      public static function count(): int
      {
          return (int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
      }

      public static function authenticate(string $email, string $password): ?array
      {
          $user = self::findByEmail($email);
          if (!$user) return null;
          if (!password_verify($password, $user['password_hash'])) return null;
          return $user;
      }
}
