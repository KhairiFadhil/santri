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

        $queue = Antrian::getAntrianAktif((int)$userId);
        if (!$queue) {
            echo json_encode(['ok' => true, 'queue' => null]);
            return;
        }

        $stats = Antrian::frontStats($queue);

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
                'ahead'          => (int)$stats['ahead'],
                'eta_minutes'    => (int)$stats['eta_minutes'],
                'calling'        => $stats['calling'],
                'calling_number' => (int)$stats['calling_number'],
            ],
        ]);
    }

    // dipakai home page buat polling live queue (no auth, public)
    public function live(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode([
            'ok'   => true,
            'live' => Antrian::liveQueue(),
        ]);
    }
}
