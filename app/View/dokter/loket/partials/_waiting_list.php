<?php
/** @var string $base */
/** @var array<int, array<string, mixed>> $waiting */
/** @var bool $isBusy */
/** @var bool $isOff */
?>
<div class="doctor-card">
    <div class="doctor-card-head">
        <?= doctor_icon('clock', 16) ?>
        <h2>Menunggu</h2>
        <span class="muted"><?= count($waiting ?? []) ?></span>
    </div>

    <div class="doctor-list">
        <?php if (empty($waiting)): ?>
            <div class="doctor-list-empty">Antrean kosong.</div>
        <?php else: ?>
            <?php foreach ($waiting as $i => $w): ?>
                <div class="doctor-queue-item <?= $i === 0 ? 'first' : '' ?>">
                    <span class="doctor-queue-no"><?= $i + 1 ?></span>
                    <div class="doctor-queue-info">
                        <div class="doctor-queue-code"><?= doctor_e($w['ticket_code'] ?? '-') ?></div>
                        <div class="doctor-queue-sub"><?= doctor_e($w['patient_name'] ?? '-') ?> · <?= doctor_e(!empty($w['complaint']) ? $w['complaint'] : 'Tidak ada keluhan') ?></div>
                    </div>

                    <?php if (empty($isBusy) && empty($isOff)): ?>
                        <form class="doctor-queue-actions" action="<?= $base ?>/dokter/loket/<?= (int)$w['id'] ?>/call" method="post">
                            <button class="btn btn-primary" type="submit"><?= doctor_icon('megaphone', 14) ?> Panggil</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-primary" type="button" disabled><?= doctor_icon('megaphone', 14) ?> Panggil</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
