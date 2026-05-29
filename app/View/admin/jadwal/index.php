<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1>Jadwal Dokter</h1>
        <p class="page-title-note">Atur jadwal praktik dokter per hari.</p>
    </div>
</div>

<h2>Tambah / Ubah Jadwal</h2>
<div class="form-card">
    <form action="<?= $base ?>/admin/jadwal" method="post">
        <div class="form-row">
            <p>
                <label>Dokter</label>
                <select name="doctor_id" required>
                    <option value="">Pilih Dokter</option>
                    <?php foreach (($doctors ?? []) as $doctor): ?>
                        <option value="<?= $doctor['id'] ?>">
                            <?= htmlspecialchars(($doctor['name'] ?? '') . ' - ' . ($doctor['poli_name'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label>Hari</label>
                <select name="day_of_week" required>
                    <?php foreach (($days ?? []) as $day): ?>
                        <option value="<?= $day ?>"><?= htmlspecialchars($day) ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
        <div class="form-row">
            <p>
                <label>Jam Mulai</label>
                <input type="time" name="time_start" required>
            </p>
            <p>
                <label>Jam Selesai</label>
                <input type="time" name="time_end" required>
            </p>
        </div>
        <p>
            <label>Kapasitas</label>
            <input type="number" name="capacity" value="30" min="1">
        </p>
        <div class="form-actions">
            <button type="submit">Simpan Jadwal</button>
        </div>
    </form>
</div>

<h2>Daftar Jadwal</h2>
<table>
    <thead>
        <tr>
            <th>Dokter</th>
            <th>Poli</th>
            <?php foreach (($days ?? []) as $day): ?>
                <th><?= htmlspecialchars($day) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach (($grid ?? []) as $row): ?>
            <tr>
                <td><strong><?= htmlspecialchars($row['name'] ?? '') ?></strong></td>
                <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
                <?php foreach (($days ?? []) as $day): ?>
                    <td>
                        <?php $block = $row['blocks'][$day] ?? null; ?>
                        <?php if ($block): ?>
                            <span class="badge"><?= htmlspecialchars(substr($block['time_start'] ?? '', 0, 5) . ' - ' . substr($block['time_end'] ?? '', 0, 5)) ?></span><br>
                            <small>Kapasitas: <?= htmlspecialchars((string)($block['capacity'] ?? '')) ?></small>
                            <form class="inline-form" action="<?= $base ?>/admin/jadwal/delete" method="post">
                                <input type="hidden" name="doctor_id" value="<?= $row['doctor_id'] ?>">
                                <input type="hidden" name="day_of_week" value="<?= $day ?>">
                                <button type="submit">Hapus</button>
                            </form>
                        <?php else: ?>
                            <span class="badge badge-muted">-</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>