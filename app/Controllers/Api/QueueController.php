<?php

namespace App\Controllers\Api;

use App\Model\Antrian;

class QueueController
{
    public function status(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Tidak terautentikasi.']);
            return;
        }

        $queue = Antrian::antrianAktif((int)$userId);
        if (!$queue) {
            echo json_encode(['ok' => true, 'queue' => null]);
            return;
        }

        $info = Antrian::infoAntrian($queue);

        echo json_encode([
            'ok' => true,
            'queue' => [
                'id'             => (int)$queue['id'],
                'ticket_code'    => $queue['ticket_code'],
                'number'         => (int)$queue['number'],
                'prefix'         => $queue['poli_code'],
                'status'         => $queue['status'],
                'poli_name'      => $queue['poli_name'],
                'doctor_name'    => $queue['doctor_name'],
                'ahead'          => (int)$info['ahead'],
                'eta_minutes'    => (int)$info['eta_minutes'],
                'calling'        => $info['calling'],
                'calling_number' => (int)$info['calling_number'],
            ],
        ]);
    }

    public function live(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode([
            'ok'   => true,
            'live' => Antrian::antrianLive(),
        ]);
    }
}
