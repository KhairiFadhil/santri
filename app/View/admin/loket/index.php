<?php $base = '/santri-belajar/public'; ?>

<h1>Monitoring Loket</h1>
<p><small>Tampilan pantau. Pemanggilan antrean dilakukan dokter di loketnya masing-masing.</small></p>

<form method="get" action="<?= $base ?>/admin/loket">
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
        <button type="submit">Filter</button>
    </p>
</form>

<h2>Menunggu</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Poli</th><th>Dokter</th></tr>
    <?php foreach (($waiting ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Dipanggil</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Dokter</th></tr>
    <?php foreach (($called ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Diproses</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Dokter</th></tr>
    <?php foreach (($progress ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Selesai</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Poli</th></tr>
    <?php foreach (($done ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
