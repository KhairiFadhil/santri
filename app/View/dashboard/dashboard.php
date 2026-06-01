<?php
/** @var array $user */
/** @var ?array $aktif */
/** @var array $frontStats */

$status = strtolower($aktif['status'] ?? '');

$isDipanggil = in_array($status, ['dipanggil', 'called', 'call']);
$isMenunggu = in_array($status, ['menunggu', 'waiting', 'wait']);

$ticketCode = $aktif['ticket_code'] ?? '-';
$poliName = $aktif['poli_name'] ?? '-';
$doctorName = $aktif['doctor_name'] ?? '-';
$scheduleDate = $aktif['schedule_date'] ?? null;

$tanggalText = $scheduleDate
    ? format_tanggal_id($scheduleDate)
    : '-';

$jamPraktik = $aktif['practice_time']
    ?? $aktif['schedule_time']
    ?? $aktif['start_time']
    ?? $aktif['jam_praktik']
    ?? '-';

$jenisPasien = $aktif['patient_type']
    ?? $aktif['jenis_pasien']
    ?? $aktif['tipe_pasien']
    ?? $aktif['insurance_type']
    ?? 'BPJS';

$nomorDipanggil = $frontStats['calling'] ?? '-';
?>

<div class="user-page dashboard-antrean-page">
    <div class="dashboard-greeting">
        <p>Halo, <?= htmlspecialchars($user['name']) ?> 👋</p>
        <h1>Antrean Aktif</h1>
    </div>

    <?php if ($aktif): ?>
        <section class="queue-summary card dashboard-ticket-card">
            <div class="queue-summary-main">
                <div class="queue-top-row">
                    <span class="eyebrow">Nomor Antrean</span>
                    <span class="queue-type"><?= htmlspecialchars(strtoupper($jenisPasien)) ?></span>
                </div>

                <div class="queue-ticket mono">
                    <?= htmlspecialchars($ticketCode) ?>
                </div>

                <div class="queue-subtitle">
                    <strong><?= htmlspecialchars($poliName) ?></strong>
                    <span>·</span>
                    <strong><?= htmlspecialchars($doctorName) ?></strong>
                </div>

                <div class="queue-line"></div>

                <div class="queue-call-message">
                    <?php if ($isDipanggil): ?>
                        <strong>🔔 Giliran Anda!</strong>
                        <p>Silakan menuju <?= htmlspecialchars($poliName) ?></p>
                    <?php elseif ($isMenunggu): ?>
                        <strong>⏳ Menunggu Giliran</strong>
                        <p>Mohon pantau nomor antrean Anda</p>
                    <?php else: ?>
                        <strong>📌 Status Antrean</strong>
                        <p><?= htmlspecialchars(strtoupper($aktif['status'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="card dashboard-call-card">
            <div class="calling-icon">
                📣
            </div>

            <div class="calling-text">
                <span>Sedang dipanggil di loket</span>
                <strong class="mono"><?= htmlspecialchars($nomorDipanggil ?: '-') ?></strong>
            </div>

            <i class="calling-dot"></i>
        </section>

        <section class="card dashboard-detail-card">
            <div class="dashboard-detail-row">
                <span>Poli</span>
                <strong><?= htmlspecialchars($poliName) ?></strong>
            </div>

            <div class="dashboard-detail-row">
                <span>Dokter</span>
                <strong><?= htmlspecialchars($doctorName) ?></strong>
            </div>

            <div class="dashboard-detail-row">
                <span>Jadwal</span>
                <strong><?= htmlspecialchars($tanggalText) ?></strong>
            </div>

            <div class="dashboard-detail-row">
                <span>Jam praktik</span>
                <strong><?= htmlspecialchars($jamPraktik) ?></strong>
            </div>
        </section>

        <div class="dashboard-detail-button">
            <a class="btn btn-secondary btn-block" href="<?= BASE_URL ?>/antrean">
                Lihat Detail Antrean
            </a>
        </div>
    <?php else: ?>
        <section class="empty-state">
            <h3>Belum ada antrean aktif</h3>
            <p>Ambil nomor antrean sesuai jadwal dokter yang tersedia.</p>
            <div class="mt-2">
                <a class="btn btn-primary" href="<?= BASE_URL ?>/daftar">
                    Daftar Antrean Sekarang
                </a>
            </div>
        </section>
    <?php endif; ?>