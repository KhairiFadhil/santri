<?php
namespace App\Core;

class Middleware
{
    public static function run($role){
        switch($role) {
            case "auth":
                if(!isset($_SESSION['user'])){
                    header('Location: /santri-belajar/public/login');
                    exit;
                }
                break;
            case "guest":
                if(isset($_SESSION['user'])){
                    header('Location: /santri-belajar/public/dashboard');
                    exit;
                }
                break;
            case "staff":
                if(!isset($_SESSION['staff'])){
                    header('Location: /santri-belajar/public/admin/login');
                    exit;
                }
                if (($_SESSION['staff']['role'] ?? '') === 'dokter') {
                    header('Location: /santri-belajar/public/dokter/loket');
                    exit;
                }
                break;
            case "admin":
                if(!isset($_SESSION['staff'])){
                    header('Location: /santri-belajar/public/admin/login');
                    exit;
                }
                if (($_SESSION['staff']['role'] ?? '') === 'dokter') {
                    header('Location: /santri-belajar/public/dokter/loket');
                    exit;
                }
                if (($_SESSION['staff']['role'] ?? '') !== 'admin') {
                    $_SESSION['flash'] = ['kind' => 'warn', 'message' => 'Halaman itu khusus admin.'];
                    header('Location: /santri-belajar/public/admin');
                    exit;
                }
                break;
            case "dokter":
                if(!isset($_SESSION['staff']) || ($_SESSION['staff']['role'] ?? '') !== 'dokter') {
                    header('Location: /santri-belajar/public/admin/login');                                                              
                    exit; 
                }
                break;
            
        }

    } 
}
