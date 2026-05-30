<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1>Monitoring Loket</h1>
        <p class="page-title-note">Pantau status antrean pasien hari ini.</p>
    </div>
</div>

<div class="filter-box">
    <form method="get" action="<?= $base ?>/admin/loket">
        <label>Poli
            <select name="poli_id">
                <option value="">Semua</option>
                <?php foreach (($poli ?? []) as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (string)($filter['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Filter</button>
    </form>
</div>

<h2>Menunggu</h2>
<table>
    <thead><tr><th>Kode</th><th>Pasien</th><th>Poli</th><th>Dokter</th></tr></thead>
    <tbody>
    <?php foreach (($waiting ?? []) as $row): ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['ticket_code'] ?? '') ?></strong></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Dipanggil</h2>
<table>
    <thead><tr><th>Kode</th><th>Pasien</th><th>Dokter</th></tr></thead>
    <tbody>
    <?php foreach (($called ?? []) as $row): ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['ticket_code'] ?? '') ?></strong></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Diproses</h2>
<table>
    <thead><tr><th>Kode</th><th>Pasien</th><th>Dokter</th></tr></thead>
    <tbody>
    <?php foreach (($progress ?? []) as $row): ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['ticket_code'] ?? '') ?></strong></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Selesai</h2>
<table>
    <thead><tr><th>Kode</th><th>Pasien</th><th>Poli</th></tr></thead>
    <tbody>
    <?php foreach (($done ?? []) as $row): ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['ticket_code'] ?? '') ?></strong></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>