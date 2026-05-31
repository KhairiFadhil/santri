<?php $base = BASE_URL; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">

<div class="section-header">
    <div>
        <h1>Manajemen Antrean</h1>
        <p class="page-title-note">Pantau antrean berdasarkan poli dan status.</p>
    </div>
</div>

<?php if (!empty($summary)): ?>
<div class="stats-grid" style="margin-bottom: 1.5rem;">
    <?php foreach (($summary ?? []) as $status => $jumlah): ?>
        <div class="stat-card">
            <span><?= htmlspecialchars(strtoupper($status)) ?></span>
            <strong><?= htmlspecialchars((string)$jumlah) ?></strong>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="filter-box">
    <form method="get" action="<?= $base ?>/admin/antrean">
        <label class="form-label">Poli
            <select name="poli_id" class="form-control">
                <option value="">Semua</option>
                <?php foreach (($poli ?? []) as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (string)($filter['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="form-label">Status
            <select name="status" class="form-control">
                <option value="">Semua</option>
                <?php foreach (['wait','call','progress','done','skip','cancel'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filter['status'] ?? '') === $s ? 'selected' : '' ?>><?= strtoupper($s) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<?php if (empty($rows)): ?>
    <div class="empty-state">Tidak ada data antrean.</div>
<?php else: ?>
<div class="card table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Pasien</th>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($rows ?? []) as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($row['ticket_code'] ?? '') ?></strong></td>
                    <td><?= htmlspecialchars($row['patient_name'] ?? $row['user_name'] ?? $row['walkin_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['schedule_date'] ?? '') ?></td>
                    <td><?= htmlspecialchars(substr($row['schedule_time'] ?? '', 0, 5)) ?></td>
                    <td><span class="badge"><?= htmlspecialchars(strtoupper($row['status'] ?? '')) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>