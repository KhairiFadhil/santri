<?php $base = BASE_URL; ?>

<div class="section-header mb-3">
    <div class="flex justify-between items-center">
        <div>
            <h1>Monitoring Loket</h1>
            <p class="page-title-note">Tampilan pantau. Pemanggilan antrean dilakukan dokter di loketnya masing-masing.</p>
        </div>
    </div>
</div>

<form method="get" action="<?= $base ?>/admin/loket" class="filter-box">
        <label class="form-label">Poli</label>
        <select name="poli_id" class="form-control" style="max-width: 200px;">
            <option value="">Semua</option>
            <?php foreach (($poli ?? []) as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (string)($filter['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
</form>

<h2 style="color: var(--teks-2);">Menunggu</h2>
<div class="card table-wrapper">
    <table class="table">
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
</div>

<h2 style="color: var(--teks-2);">Dipanggil</h2>
<div class="card table-wrapper">
<table class="table">
    <tr><th>Kode</th><th>Pasien</th><th>Dokter</th></tr>
    <?php foreach (($called ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<h2 style="color: var(--teks-2);">Diproses</h2>
<div class="card table-wrapper">
<table class="table">
    <tr><th>Kode</th><th>Pasien</th><th>Dokter</th></tr>
    <?php foreach (($progress ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<h2 style="color: var(--teks-2);">Selesai</h2>
<div class="card table-wrapper">
<table class="table">
    <tr><th>Kode</th><th>Pasien</th><th>Poli</th></tr>
    <?php foreach (($done ?? []) as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['patient_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
