<?php
/** @var string $base */
/** @var string $today */
/** @var bool $isOff */
/** @var string $doctorName */
/** @var string $poliName */
?>
<header class="doctor-topbar">
    <a class="doctor-wordmark" href="<?= $base ?>/dokter/loket">
        <?php
        $logoSize = 38;
        include __DIR__ . '/../../../layout/_logo.php';
        ?>
        <span>
            <span class="doctor-brand-title">SANTRI</span>
            <span class="doctor-brand-sub">Sistem Antrian RS</span>
        </span>
    </a>

    <div class="doctor-divider"></div>

    <div>
        <div class="doctor-page-title">Loket Dokter</div>
        <div class="doctor-page-date"><?= doctor_e($today) ?></div>
    </div>

    <div class="doctor-top-actions">
        <?= doctor_badge(!empty($isOff) ? 'cancel' : 'done') ?>

        <?php if (!empty($isOff)): ?>
            <form action="<?= $base ?>/dokter/loket/buka" method="post">
                <button class="btn btn-success" type="submit"><?= doctor_icon('check', 15) ?> Buka Praktik</button>
            </form>
        <?php else: ?>
            <form action="<?= $base ?>/dokter/loket/tutup" method="post" onsubmit="return confirm('Tutup praktik hari ini? Pasien tidak bisa daftar lagi.')">
                <button class="btn btn-danger" type="submit"><?= doctor_icon('x', 15) ?> Tutup Praktik</button>
            </form>
        <?php endif; ?>

        <div class="doctor-userchip">
            <div class="avatar"><?= doctor_e(doctor_initial($doctorName)) ?></div>
            <div>
                <div class="doctor-user-name"><?= doctor_e($doctorName) ?></div>
                <div class="doctor-user-role"><?= doctor_e($poliName) ?></div>
            </div>
        </div>

        <a class="admin-icon" href="<?= $base ?>/admin/logout" title="Keluar">
            <?= doctor_icon('logout', 18) ?>
        </a>
    </div>
</header>
