<?php $base = '/santri-belajar/public'; ?>

<h1>Manajemen Antrean</h1>

<h2>Ringkasan</h2>
<ul>
    <?php foreach (($summary ?? []) as $status => $jumlah): ?>
        <li><?= htmlspecialchars($status) ?>: <?= htmlspecialchars((string)$jumlah) ?></li>
    <?php endforeach; ?>
</ul>

<form method="get" action="<?= $base ?>/admin/antrean">
    <p>
        <label>Poli</label>
        <select name="poli_id">
            <option value="">Semua</option>
            <?php foreach (($poli ?? []) as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (string)($filter['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Status</label>
        <select name="status">
            <option value="">Semua</option>
            <?php foreach (['wait','call','progress','done','skip','cancel'] as $s): ?>
                <option value="<?= $s ?>" <?= ($filter['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </p>
</form>

<table border="1" cellpadding="5">
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
    <?php foreach (($rows ?? []) as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? $row['user_name'] ?? $row['walkin_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['schedule_date'] ?? '') ?></td>
            <td><?= htmlspecialchars(substr($row['schedule_time'] ?? '', 0, 5)) ?></td>
            <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
