<?php $base = BASE_URL; ?>

<div class="section-header mb-3">
    <div class="flex justify-between items-center">
        <div>
            <h1>Jadwal Dokter</h1>
            <p class="page-title-note">Atur jadwal praktik dokter per hari.</p>
        </div>
    </div>
</div>

<div class="card mb-3">
    <h2 class="card-title">Tambah / Ubah Jadwal</h2>
    <form action="<?= $base ?>/admin/jadwal" method="post">
        <div class="flex gap-2 form-row mb-2">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Dokter</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">Pilih Dokter</option>
                    <?php foreach (($doctors ?? []) as $doctor): ?>
                        <option value="<?= $doctor['id'] ?>">
                            <?= htmlspecialchars(($doctor['name'] ?? '') . ' - ' . ($doctor['poli_name'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Hari</label>
                <select name="day_of_week" class="form-control" required>
                    <?php foreach (($days ?? []) as $day): ?>
                        <option value="<?= $day ?>"><?= htmlspecialchars($day) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="flex gap-2 form-row mb-2">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="time_start" class="form-control" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="time_end" class="form-control" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Kapasitas</label>
                <input type="number" name="capacity" class="form-control" value="30" min="1">
            </div>
        </div>

        <div class="form-actions mt-2">
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </div>
    </form>
</div>

<div class="card table-wrapper">
    <h2 class="card-title">Daftar Jadwal</h2>
    <table class="table jadwal-table">
        <thead>
            <tr>
                <th style="min-width: 150px;">Dokter</th>
                <th>Poli</th>
                <?php foreach (($days ?? []) as $day): ?>
                    <th class="text-center"><?= htmlspecialchars($day) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($grid ?? []) as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['name'] ?? '') ?></strong></td>
                    <td><span class="badge badge-success"><?= htmlspecialchars($row['poli_name'] ?? '') ?></span></td>

                    <?php foreach (($days ?? []) as $day): ?>
                        <td class="text-center" style="vertical-align: top;">
                            <?php $block = $row['blocks'][$day] ?? null; ?>
                            <?php if ($block): ?>
                                <div class="schedule-block">
                                    <div class="schedule-time">
                                        <?= htmlspecialchars(substr($block['time_start'] ?? '', 0, 5) . ' - ' . substr($block['time_end'] ?? '', 0, 5)) ?>
                                    </div>
                                    <div class="schedule-cap">
                                        Kapasitas: <?= htmlspecialchars((string)($block['capacity'] ?? '')) ?>
                                    </div>
                                    <form class="inline-form" action="<?= $base ?>/admin/jadwal/delete" method="post" style="margin-top: 8px;">
                                        <input type="hidden" name="doctor_id" value="<?= $row['doctor_id'] ?>">
                                        <input type="hidden" name="day_of_week" value="<?= $day ?>">
                                        <button type="submit" class="btn-delete-sm" onclick="return confirm('Hapus jadwal ini?')">Hapus</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="badge-muted">-</span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
