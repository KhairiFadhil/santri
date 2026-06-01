<?php /** @var array $rows */ ?>

<div class="user-page">
    <div class="section-header user-section-head">
        <div>
            <h1>Riwayat Antrean</h1>
            <p class="page-title-note">Daftar kunjungan dan status antrean Anda.</p>
        </div>
        <a class="btn btn-ghost" href="<?= BASE_URL ?>/dashboard">← Dashboard</a>
    </div>

    <?php if (empty($rows)): ?>
        <div class="empty-state">
            <h3>Belum ada riwayat antrean</h3>
            <p>Riwayat akan muncul setelah Anda pernah mengambil antrean.</p>
        </div>
    <?php else: ?>
        <section class="card card-pad">
            <div class="history-list">
                <?php foreach ($rows as $r): ?>
                    <div class="history-card">
                        <div class="history-main">
                            <span class="mono history-ticket"><?= htmlspecialchars($r['ticket_code']) ?></span>
                            <strong><?= htmlspecialchars($r['poli_name']) ?></strong>
                            <span><?= htmlspecialchars($r['doctor_name']) ?></span>
                        </div>
                        <div class="history-meta">
                            <span><?= htmlspecialchars(format_tanggal_id($r['schedule_date'])) ?></span>
                            <span class="badge <?= htmlspecialchars($r['status']) ?>"><i></i><?= htmlspecialchars(strtoupper($r['status'])) ?></span>
                        </div>
                        <div class="history-complaint">
                            <span>Keluhan</span>
                            <p><?= htmlspecialchars($r['complaint'] ?? '-') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>