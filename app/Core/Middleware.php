<?php
namespace App\Core;

class Middleware
{
    public static function run(string $name): void
    {
        switch ($name) {
            case 'auth':
                if (!isset($_SESSION['user'])) {
                    header('Location: /santri-belajar/public/login');
                    exit;
                }
                break;

            case 'guest':
                if (isset($_SESSION['user'])) {
                    header('Location: /santri-belajar/public/dashboard');
                    exit;
                }
                if (isset($_SESSION['staff'])) {
                    header('Location: /santri-belajar/public/admin');
                    exit;
                }
                break;

            case 'staff':
                if (!isset($_SESSION['staff'])) {
                    header('Location: /santri-belajar/public/admin/login');
                    exit;
                }
                // dokter bukan staff loket, lempar ke loketnya sendiri
                if (($_SESSION['staff']['role'] ?? '') === 'dokter') {
                    header('Location: /santri-belajar/public/dokter/loket');
                    exit;
                }
                break;

            case 'dokter':
                if (!isset($_SESSION['staff']) || ($_SESSION['staff']['role'] ?? '') !== 'dokter') {
                    header('Location: /santri-belajar/public/admin/login');
                    exit;
                }
                break;
        }
    }
}
