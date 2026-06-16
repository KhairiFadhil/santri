<?php
/** @var array<int, array<string, mixed>> $waiting */
/** @var array<string, mixed>|null $now */
/** @var array<int, array<string, mixed>> $doneOnly */
/** @var int|float $avgMinutes */
?>
<section class="doctor-stats">
    <div class="doctor-stat">
        <div class="doctor-stat-icon stat-icon-wait"><?= doctor_icon('clock', 21) ?></div>
        <div>
            <div class="doctor-stat-value"><?= count($waiting ?? []) ?><small> pasien</small></div>
            <div class="doctor-stat-label">Menunggu</div>
        </div>
    </div>

    <div class="doctor-stat">
        <div class="doctor-stat-icon stat-icon-progress"><?= doctor_icon('megaphone', 21) ?></div>
        <div>
            <div class="doctor-stat-value"><?= !empty($now) ? 1 : 0 ?><small> loket</small></div>
            <div class="doctor-stat-label">Sedang dilayani</div>
        </div>
    </div>

    <div class="doctor-stat">
        <div class="doctor-stat-icon stat-icon-done"><?= doctor_icon('check', 21) ?></div>
        <div>
            <div class="doctor-stat-value"><?= count($doneOnly) ?><small> pasien</small></div>
            <div class="doctor-stat-label">Selesai hari ini</div>
        </div>
    </div>

    <div class="doctor-stat">
        <div class="doctor-stat-icon stat-icon-brand"><?= doctor_icon('pulse', 21) ?></div>
        <div>
            <div class="doctor-stat-value"><?= $avgMinutes ?><small> mnt</small></div>
            <div class="doctor-stat-label">Rata-rata layanan</div>
        </div>
    </div>
</section>
