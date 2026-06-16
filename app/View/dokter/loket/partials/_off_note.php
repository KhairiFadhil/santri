<?php
/** @var bool $isOff */
?>
<?php if (!empty($isOff)): ?>
    <div class="doctor-off-note">
        <?= doctor_icon('shield', 18) ?>
        <span>Praktik hari ini sedang <strong>ditutup</strong>. Pasien baru tidak bisa mengambil antrean untuk dokter ini.</span>
    </div>
<?php endif; ?>
