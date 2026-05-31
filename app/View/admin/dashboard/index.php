<?php
/** @var array $stats */
/** @var array $antrean */
/** @var array $poli */
/** @var array $dokter */
$base = BASE_URL;
?>

<div class="section-header">
    <div>
        <h1>Dashboard Admin</h1>
        <p class="page-title-note">Ringkasan data poli, dokter, pasien, dan antrean hari ini.</p>
    </div>
    <div class="section-actions">
        <a class="btn btn-primary" href="<?= $base ?>/admin/loket">Buka Loket</a>
        <a class="btn btn-secondary" href="<?= $base ?>/admin/walkin">Pendaftaran Walk-in</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><span>Total Poli </span><strong><?= $stats['poli'] ?></strong></div>
    <div class="stat-card"><span>Poli Buka </span><strong><?= $stats['poli_buka'] ?></strong></div>
    <div class="stat-card"><span>Dokter Aktif </span><strong><?= $stats['dokter_aktif'] ?></strong></div>
    <div class="stat-card"><span>Total Pasien </span><strong><?= $stats['pasien'] ?></strong></div>
    <div class="stat-card"><span>Total Petugas </span><strong><?= $stats['petugas'] ?></strong></div>
    <div class="stat-card"><span>Antrean Hari Ini </span><strong><?= $stats['antrean_hari_ini'] ?></strong></div>
</div>

<h2>Status Antrean Hari Ini</h2>
<div class="stats-grid">
    <div class="stat-card"><span>Menunggu </span><strong><?= $stats['menunggu'] ?></strong></div>
    <div class="stat-card"><span>Dipanggil </span><strong><?= $stats['dipanggil'] ?></strong></div>
    <div class="stat-card"><span>Diproses </span><strong><?= $stats['diproses'] ?></strong></div>
    <div class="stat-card"><span>Selesai </span><strong><?= $stats['selesai'] ?></strong></div>
    <div class="stat-card"><span>Dilewati </span><strong><?= $stats['dilewati'] ?></strong></div>
    <div class="stat-card"><span>Batal </span><strong><?= $stats['batal'] ?></strong></div>
</div>

<h2>Antrean Hari Ini (<?= count($antrean) ?>)</h2>
<?php if (empty($antrean)): ?>
    <div class="empty-state">Belum ada antrean hari ini.</div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Pasien</th>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($antrean as $a): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($a['ticket_code']) ?></strong></td>
                    <td><?= htmlspecialchars($a['patient_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($a['poli_name']) ?></td>
                    <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                    <td><?= htmlspecialchars(substr($a['schedule_time'] ?? '-', 0, 5)) ?></td>
                    <td><span class="badge"><?= htmlspecialchars(strtoupper($a['status'])) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Menu Admin</h2>
<div class="menu-grid">
    <a class="menu-card" href="<?= $base ?>/admin/loket"><h3>Loket Pemanggilan</h3><p>Pantau antrean yang menunggu, dipanggil, dan diproses.</p></a>
    <a class="menu-card" href="<?= $base ?>/admin/antrean"><h3>Manajemen Antrean</h3><p>Filter dan cek daftar antrean pasien.</p></a>
    <a class="menu-card" href="<?= $base ?>/admin/walkin"><h3>Pendaftaran Walk-in</h3><p>Input antrean pasien yang datang langsung.</p></a>
    <a class="menu-card" href="<?= $base ?>/admin/poli"><h3>Kelola Poli</h3><p><?= count($poli) ?> data poli tersedia.</p></a>
    <a class="menu-card" href="<?= $base ?>/admin/dokter"><h3>Kelola Dokter</h3><p><?= count($dokter) ?> data dokter tersedia.</p></a>
    <a class="menu-card" href="<?= $base ?>/admin/jadwal"><h3>Jadwal Dokter</h3><p>Atur hari praktik, jam, dan kapasitas antrean.</p></a>
    <a class="menu-card" href="<?= $base ?>/admin/pasien"><h3>Database Pasien</h3><p>Lihat dan kelola data pasien terdaftar.</p></a>
</div>
