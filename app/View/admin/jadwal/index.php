<?php $base = '/santri-belajar/public'; ?>

<h1>Jadwal Dokter</h1>

<h2>Tambah / Ubah Jadwal</h2>
<form action="<?= $base ?>/admin/jadwal" method="post">
    <p>
        <label>Dokter</label><br>
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
        <label>Hari</label><br>
        <select name="day_of_week" required>
            <?php foreach (($days ?? []) as $day): ?>
                <option value="<?= $day ?>"><?= htmlspecialchars($day) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Jam Mulai</label><br>
        <input type="time" name="time_start" required>
    </p>
    <p>
        <label>Jam Selesai</label><br>
        <input type="time" name="time_end" required>
    </p>
    <p>
        <label>Kapasitas</label><br>
        <input type="number" name="capacity" value="30" min="1">
    </p>
    <button type="submit">Simpan Jadwal</button>
</form>

<h2>Daftar Jadwal</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Dokter</th>
        <th>Poli</th>
        <?php foreach (($days ?? []) as $day): ?>
            <th><?= htmlspecialchars($day) ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach (($grid ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
            <?php foreach (($days ?? []) as $day): ?>
                <td>
                    <?php $block = $row['blocks'][$day] ?? null; ?>
                    <?php if ($block): ?>
                        <?= htmlspecialchars(($block['time_start'] ?? '') . ' - ' . ($block['time_end'] ?? '')) ?><br>
                        Kapasitas: <?= htmlspecialchars((string)($block['capacity'] ?? '')) ?>
                        <form action="<?= $base ?>/admin/jadwal/delete" method="post">
                            <input type="hidden" name="doctor_id" value="<?= $row['doctor_id'] ?>">
                            <input type="hidden" name="day_of_week" value="<?= $day ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
