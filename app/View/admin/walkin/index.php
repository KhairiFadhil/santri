<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1>Pendaftaran Walk-in</h1>
        <p class="page-title-note">Input pasien yang datang langsung ke rumah sakit.</p>
    </div>
</div>

<div class="card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-3">
            <ul style="margin: 0; padding-left: 18px;">
                <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= $base ?>/admin/walkin" method="post">

        <div class="flex gap-2 mb-2">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Nama Pasien</label>
                <input type="text" name="walkin_name" class="form-control" value="<?= htmlspecialchars($form['walkin_name'] ?? '') ?>" placeholder="Contoh: Siti Aminah" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">No HP</label>
                <input type="text" name="walkin_phone" class="form-control" value="<?= htmlspecialchars($form['walkin_phone'] ?? '') ?>" placeholder="Contoh: 081234567890" required>
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">NIK</label>
            <input type="text" name="walkin_nik" class="form-control" value="<?= htmlspecialchars($form['walkin_nik'] ?? '') ?>" placeholder="16 Digit NIK KTP" required maxlength="16">
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Dokter Tujuan
                <span style="color: var(--ink-4); font-weight: 500;">— praktik hari ini (<?= htmlspecialchars(format_tanggal_id(date('Y-m-d'))) ?>)</span>
            </label>
            <?php if (empty($doctors)): ?>
                <div class="empty-state" style="padding: 26px 20px;">
                    <h3>Tidak ada dokter praktik</h3>
                    <p>Belum ada dokter yang sedang praktik saat ini. Coba lagi nanti.</p>
                </div>
            <?php else: ?>
                <div class="doctor-choice-grid">
                    <?php foreach ($doctors as $d): ?>
                        <?php $full = !empty($d['penuh']); ?>
                        <label class="doctor-card <?= $full ? 'is-disabled' : '' ?>">
                            <div class="doctor-radio">
                                <?php if ($full): ?>
                                    <input type="radio" disabled>
                                <?php else: ?>
                                    <input type="radio" name="doctor_id" value="<?= (int)$d['id'] ?>"
                                           <?= (string)($form['doctor_id'] ?? '') === (string)$d['id'] ? 'checked' : '' ?> required>
                                <?php endif; ?>
                            </div>
                            <div class="doctor-info">
                                <strong><?= htmlspecialchars($d['name']) ?></strong>
                                <span><?= htmlspecialchars($d['specialization'] ?? '') ?></span>
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
            <?php endif; ?>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Keluhan <span style="color: var(--ink-4); font-weight: 500;">(opsional)</span></label>
            <textarea name="complaint" class="form-control" rows="3" placeholder="Keluhan atau gejala pasien — boleh dikosongkan"><?= htmlspecialchars($form['complaint'] ?? '') ?></textarea>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Daftarkan Pasien</button>
            <a class="btn btn-danger" href="<?= $base ?>/admin">Kembali ke Dashboard</a>
        </div>

    </form>
</div>
