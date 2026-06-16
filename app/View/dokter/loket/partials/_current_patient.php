<?php
/** @var string $base */
/** @var array<string, mixed>|null $now */
/** @var array<string, mixed>|null $firstWaiting */
/** @var bool $isOff */
?>
<div class="doctor-card">
    <div class="doctor-card-head">
        <?= doctor_icon('megaphone', 17) ?>
        <h2>Sedang Dilayani</h2>
    </div>

    <div class="doctor-card-body">
        <?php if (!empty($now)): ?>
            <div class="doctor-ticket-box">
                <div class="doctor-ticket-code"><?= doctor_e($now['ticket_code'] ?? '-') ?></div>
                <div class="ticket-badge-wrap"><?= doctor_badge($now['status'] ?? 'call') ?></div>
            </div>

            <div class="doctor-patient-row">
                <div class="avatar"><?= doctor_e(doctor_initial($now['patient_name'] ?? 'Pasien')) ?></div>
                <div>
                    <div class="doctor-patient-name"><?= doctor_e($now['patient_name'] ?? '-') ?></div>
                    <div class="doctor-patient-meta"><?= doctor_e(($now['registered_via'] ?? 'online') === 'walkin' ? 'Pasien walk-in' : 'Pasien online') ?></div>
                </div>
                <span class="tag doctor-patient-tag"><?= doctor_e($now['insurance_type'] ?? '-') ?></span>
            </div>

            <div class="doctor-detail-grid">
                <div class="doctor-detail-item">
                    <div class="doctor-detail-label">Keluhan</div>
                    <div class="doctor-detail-value"><?= doctor_e(!empty($now['complaint']) ? $now['complaint'] : '-') ?></div>
                </div>
                <div class="doctor-detail-item">
                    <div class="doctor-detail-label">Jam Daftar</div>
                    <div class="doctor-detail-value mono">
                        <?= doctor_e(!empty($now['created_at']) ? date('H:i', strtotime($now['created_at'])) . ' WIB' : '-') ?>
                    </div>
                </div>
                <div class="doctor-detail-item">
                    <div class="doctor-detail-label">No. Antrean</div>
                    <div class="doctor-detail-value mono">#<?= doctor_e($now['number'] ?? '-') ?></div>
                </div>
                <div class="doctor-detail-item">
                    <div class="doctor-detail-label">Pendaftaran</div>
                    <div class="doctor-detail-value"><?= doctor_e(($now['registered_via'] ?? 'online') === 'walkin' ? 'Walk-in' : 'Online') ?></div>
                </div>
            </div>

            <div class="doctor-action-row">
                <?php if (($now['status'] ?? '') === 'call'): ?>
                    <form class="btn-flex" action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/progress" method="post">
                        <button class="btn btn-primary btn-lg btn-block" type="submit"><?= doctor_icon('play', 17) ?> Mulai Periksa</button>
                    </form>
                    <form action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/skip" method="post" onsubmit="return confirm('Tandai pasien tidak hadir?')">
                        <button class="btn btn-danger btn-lg" type="submit"><?= doctor_icon('x', 17) ?> Tidak Hadir</button>
                    </form>
                <?php elseif (($now['status'] ?? '') === 'progress'): ?>
                    <form class="btn-flex" action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/done" method="post">
                        <button class="btn btn-success btn-lg btn-block" type="submit"><?= doctor_icon('check', 17) ?> Selesai Periksa</button>
                    </form>
                    <form action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/done-next" method="post">
                        <button class="btn btn-primary btn-lg" type="submit"><?= doctor_icon('next', 17) ?> Selesai &amp; Panggil Berikutnya</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="doctor-empty-symbol">—</div>
                <div class="empty-text">Tidak ada pasien dilayani.<br>Panggil pasien berikutnya dari daftar.</div>

                <?php if (!empty($firstWaiting) && empty($isOff)): ?>
                    <form action="<?= $base ?>/dokter/loket/<?= (int)$firstWaiting['id'] ?>/call" method="post" class="empty-call-form">
                        <button class="btn btn-primary btn-lg" type="submit"><?= doctor_icon('megaphone', 17) ?> Panggil <?= doctor_e($firstWaiting['ticket_code'] ?? '') ?></button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

