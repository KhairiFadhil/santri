<?php

namespace App\Model;

use PDO;

class Staff
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM staff ORDER BY role, name')->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM staff WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $st = db()->prepare('SELECT * FROM staff WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $st = db()->prepare('INSERT INTO staff (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)');
        $st->execute([
            $data['name'],
            $data['email'],
            $hash,
            $data['role'] ?? 'petugas',
            $data['status'] ?? 'offline',
        ]);
        return (int)db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $allowed = ['name','email','role','status'];
        $sets = [];
        $params = [];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $sets[] = "$col = ?";
                $params[] = $data[$col];
            }
        }
        if (!empty($data['password'])) {
            $sets[] = 'password_hash = ?';
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }
        if (empty($sets)) return false;
        $params[] = $id;
        $st = db()->prepare('UPDATE staff SET ' . implode(', ', $sets) . ' WHERE id = ?');
        return $st->execute($params);
    }

    public static function delete(int $id): bool
    {
        $st = db()->prepare('DELETE FROM staff WHERE id = ?');
        return $st->execute([$id]);
    }

    public static function authenticate(string $email, string $password): ?array
    {
        $s = self::findByEmail($email);
        if (!$s) return null;
        if (!password_verify($password, $s['password_hash'])) return null;

        // set status online setelah login
        db()->prepare('UPDATE staff SET status = "online" WHERE id = ?')->execute([$s['id']]);
        return $s;
    }

    public static function setStatus(int $id, string $status): bool
    {
        $st = db()->prepare('UPDATE staff SET status = ? WHERE id = ?');
        return $st->execute([$status, $id]);
    }
}
