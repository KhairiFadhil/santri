<?php
/** @var array $doctors */
/** @var array $errors */
/** @var array $form */
/** @var string $hari */
?>

<div class="user-page">
    <div class="section-header user-section-head">
        <div>
            <h1>Daftar Antrean</h1>
            <p class="page-title-note">Pilih tanggal kunjungan, dokter, lalu ambil nomor antrean.</p>
        </div>
        <a class="btn btn-ghost" href="<?= BASE_URL ?>/dashboard">← Dashboard</a>
    </div>

    <section class="card card-pad mb-3">
        <form class="date-filter-form" method="GET" action="<?= BASE_URL ?>/daftar">
            <label class="form-label">Pilih Tanggal Kunjungan <small>(maksimal 3 hari ke depan)</small></label>
            <div class="date-filter-row">
                <input class="form-control" type="date" name="schedule_date"
                       value="<?= htmlspecialchars($form['schedule_date']) ?>"
                       min="<?= date('Y-m-d') ?>"
                       max="<?= date('Y-m-d', strtotime('+3 days')) ?>"
                       onchange="this.form.submit()">
                <button class="btn btn-secondary" type="submit">Cek Jadwal</button>
            </div>
        </form>
        <p class="selected-date">Tanggal: <strong><?= htmlspecialchars(format_tanggal_id($form['schedule_date'])) ?></strong></p>
    </section>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($doctors)): ?>
        <div class="empty-state">
            <h3>Tidak ada dokter praktik</h3>
            <p>Tidak ada dokter yang praktik di hari <?= htmlspecialchars($hari) ?>. Silakan pilih tanggal lain.</p>
        </div>
    <?php else: ?>
        <form method="POST" action="<?= BASE_URL ?>/daftar">
            <input type="hidden" name="schedule_date" value="<?= htmlspecialchars($form['schedule_date']) ?>">

            <section class="card card-pad">
                <div class="card-title">Dokter yang Praktik</div>
                <p class="page-title-note mb-2">Pilih salah satu dokter di bawah.</p>

                <div class="doctor-choice-grid">
                    <?php foreach ($doctors as $d): ?>
                        <?php $full = !empty($d['penuh']); ?>
                        <label class="doctor-card <?= $full ? 'is-disabled' : '' ?>">
                            <div class="doctor-radio">
                                <?php if ($full): ?>
                                    <input type="radio" disabled>
                                <?php else: ?>
                                    <input type="radio" name="doctor_id" value="<?= $d['id'] ?>"
                                           <?= $form['doctor_id']==$d['id']?'checked':'' ?> required>
                                <?php endif; ?>
                            </div>
                            <div class="doctor-info">
                                <strong><?= htmlspecialchars($d['name']) ?></strong>
                                <span><?= htmlspecialchars($d['specialization']) ?></span>
                                <div class="doctor-meta">
                                    <span><?= htmlspecialchars($d['poli_name']) ?></span>
                                    <span><?= substr($d['time_start'], 0, 5) ?> – <?= substr($d['time_end'], 0, 5) ?></span>
                                </div>
                            </div>
                            <div class="quota-badge <?= $full ? 'full' : '' ?>">
                                <?= $full ? 'PENUH' : 'Sisa ' . (isset($d['sisa']) ? (int)$d['sisa'] : '-') ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label">Keluhan <span class="muted">(opsional)</span></label>
                    <textarea class="form-control" name="complaint" rows="3" placeholder="Contoh: demam, batuk, kontrol rutin"><?= htmlspecialchars($form['complaint']) ?></textarea>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary btn-lg" type="submit">Ambil Nomor Antrean</button>
                    <a class="btn btn-ghost btn-lg" href="<?= BASE_URL ?>/dashboard">Batal</a>
                </div>
            </section>
        </form>
    <?php endif; ?>
</div>