<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Model\Antrian;
use App\Model\Poli;

class LoketController
{
    private const BASE = '/santri-belajar/public';

    public function index(): void
    {
        $poliId = isset($_GET['poli_id']) && $_GET['poli_id'] !== '' ? (int)$_GET['poli_id'] : null;

        View::render('admin/loket/index', [
            'poli' => Poli::all(),
            'filter' => ['poli_id' => $poliId],
            'waiting' => Antrian::listHariIni($poliId, 'wait'),
            'called' => Antrian::listHariIni($poliId, 'call'),
            'progress' => Antrian::listHariIni($poliId, 'progress'),
            'done' => Antrian::listHariIni($poliId, 'done'),
        ], 'main');
    }

    public function call(): void
    {
        $queueId = (int)($_POST['queue_id'] ?? 0);
        $staffId = $this->staffId();

        if ($queueId === 0) {
            $queueId = $this->nextWaitingQueueId((int)($_POST['poli_id'] ?? 0));
        }

        if ($queueId > 0) {
            Antrian::setStatus($queueId, 'call', $staffId);
            $this->flash('ok', 'Antrean berhasil dipanggil.');
        } else {
            $this->flash('warn', 'Tidak ada antrean menunggu.');
        }

        $this->redirect('/admin/loket');
    }

    public function skip(): void
    {
        $this->changeStatus('skip', 'Antrean berhasil dilewati.');
    }

    public function progress(): void
    {
        $this->changeStatus('progress', 'Antrean masuk proses pelayanan.');
    }

    public function done(): void
    {
        $this->changeStatus('done', 'Antrean selesai dilayani.');
    }

    private function changeStatus(string $status, string $message): void
    {
        $queueId = (int)($_POST['queue_id'] ?? 0);

        if ($queueId <= 0) {
            $this->flash('warn', 'ID antrean tidak valid.');
            $this->redirect('/admin/loket');
        }

        Antrian::setStatus($queueId, $status, $this->staffId());
        $this->flash('ok', $message);
        $this->redirect('/admin/loket');
    }

    private function nextWaitingQueueId(int $poliId = 0): int
    {
        $sql = "SELECT id FROM queues WHERE schedule_date = CURDATE() AND status = 'wait'";
        $params = [];

        if ($poliId > 0) {
            $sql .= ' AND poli_id = ?';
            $params[] = $poliId;
        }

        $sql .= ' ORDER BY number ASC LIMIT 1';
        $st = db()->prepare($sql);
        $st->execute($params);

        return (int)($st->fetchColumn() ?: 0);
    }

    private function staffId(): ?int
    {
        return isset($_SESSION['staff']['id']) ? (int)$_SESSION['staff']['id'] : null;
    }

    private function flash(string $kind, string $message): void
    {
        $_SESSION['flash'] = compact('kind', 'message');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . self::BASE . $path);
        exit;
    }
}
