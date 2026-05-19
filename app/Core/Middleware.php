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
                break;
        }
    }
}
