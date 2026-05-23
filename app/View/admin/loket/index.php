<?php $base = '/santri-belajar/public'; ?>

<h1>Loket Pemanggilan</h1>

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

<form action="<?= $base ?>/admin/loket/call" method="post">
    <input type="hidden" name="poli_id" value="<?= htmlspecialchars((string)($filter['poli_id'] ?? '')) ?>">
    <button type="submit">Panggil Antrean Berikutnya</button>
</form>

<h2>Menunggu</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Poli</th><th>Dokter</th><th>Aksi</th></tr>
    <?php foreach (($waiting ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
            <td>
                <form action="<?= $base ?>/admin/loket/call" method="post">
                    <input type="hidden" name="queue_id" value="<?= $row['id'] ?>">
                    <button type="submit">Panggil</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Dipanggil</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Aksi</th></tr>
    <?php foreach (($called ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td>
                <form action="<?= $base ?>/admin/loket/progress" method="post">
                    <input type="hidden" name="queue_id" value="<?= $row['id'] ?>">
                    <button type="submit">Proses</button>
                </form>
                <form action="<?= $base ?>/admin/loket/skip" method="post">
                    <input type="hidden" name="queue_id" value="<?= $row['id'] ?>">
                    <button type="submit">Lewati</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Diproses</h2>
<table border="1" cellpadding="5">
    <tr><th>Kode</th><th>Pasien</th><th>Aksi</th></tr>
    <?php foreach (($progress ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td>
                <form action="<?= $base ?>/admin/loket/done" method="post">
                    <input type="hidden" name="queue_id" value="<?= $row['id'] ?>">
                    <button type="submit">Selesai</button>
                </form>
            </td>
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
