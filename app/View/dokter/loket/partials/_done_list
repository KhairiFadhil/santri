<?php
/** @var string $base */
/** @var array<int, array<string, mixed>> $selesai */
/** @var bool $isBusy */
/** @var bool $isOff */
?>
<div class="doctor-card">
    <div class="doctor-card-head">
        <?= doctor_icon('check', 16) ?>
        <h2>Selesai Hari Ini</h2>
        <span class="muted"><?= count($selesai ?? []) ?></span>
    </div>

    <div class="doctor-list done">
        <?php if (empty($selesai)): ?>
            <div class="doctor-list-empty">Belum ada.</div>
        <?php else: ?>
            <?php foreach ($selesai as $s): ?>
                <div class="doctor-done-item">
                    <span class="doctor-done-code"><?= doctor_e($s['ticket_code'] ?? '-') ?></span>
                    <span class="doctor-done-name"><?= doctor_e($s['patient_name'] ?? '-') ?></span>
                    <?= doctor_badge($s['status'] ?? '-') ?>

                    <?php if (($s['status'] ?? '') === 'skip' && empty($isBusy) && empty($isOff)): ?>
                        <form action="<?= $base ?>/dokter/loket/<?= (int)$s['id'] ?>/recall" method="post">
                            <button class="btn btn-ghost" type="submit">Ulang</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
