<?php

date_default_timezone_set('Asia/Jakarta');

function db(): PDO
{
    static $pdo = null;
    if ($pdo) return $pdo;
    try {
        $pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=santri_belajar;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        $pdo->exec("SET time_zone = '+07:00'");
    } catch (PDOException $e) {
        die('Database error: ' . $e->getMessage());
    }

    return $pdo;
}
