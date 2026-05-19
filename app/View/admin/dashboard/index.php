<?php
/** @var array $stats */
/** @var array $antrean */
/** @var array $poli */
/** @var array $dokter */
/** @var array $staff */
/** @var array $pasien */
?>

<h1>Dashboard Admin</h1>

<h2>Statistik</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr><th>Total Poli</th><td><?= $stats['poli'] ?></td>
        <th>Poli Buka</th><td><?= $stats['poli_buka'] ?></td></tr>
    <tr><th>Dokter Aktif</th><td><?= $stats['dokter_aktif'] ?></td>
        <th>Total Pasien</th><td><?= $stats['pasien'] ?></td></tr>
    <tr><th>Total Petugas</th><td><?= $stats['petugas'] ?></td>
        <th>Antrean Hari Ini</th><td><?= $stats['antrean_hari_ini'] ?></td></tr>
</table>

<h2>Status Antrean Hari Ini</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>Menunggu</th><th>Dipanggil</th><th>Diproses</th>
        <th>Selesai</th><th>Dilewati</th><th>Batal</th>
    </tr>
    <tr>
        <td><?= $stats['menunggu'] ?></td>
        <td><?= $stats['dipanggil'] ?></td>
        <td><?= $stats['diproses'] ?></td>
        <td><?= $stats['selesai'] ?></td>
        <td><?= $stats['dilewati'] ?></td>
        <td><?= $stats['batal'] ?></td>
    </tr>
</table>

<h2>Antrean Hari Ini (<?= count($antrean) ?>)</h2>
<?php if (empty($antrean)): ?>
    <p>Belum ada antrean hari ini.</p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
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
                    <td><?= htmlspecialchars($a['ticket_code']) ?></td>
                    <td><?= htmlspecialchars($a['patient_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($a['poli_name']) ?></td>
                    <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($a['schedule_time'] ?? '-') ?></td>
                    <td><strong><?= htmlspecialchars(strtoupper($a['status'])) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Menu Admin</h2>
<ul>
    <li><a href="/santri-belajar/public/admin/loket">Loket Pemanggilan</a></li>
    <li><a href="/santri-belajar/public/admin/antrean">Manajemen Antrean</a></li>
    <li><a href="/santri-belajar/public/admin/walkin">Pendaftaran Walk-in</a></li>
    <li><a href="/santri-belajar/public/admin/poli">Kelola Poli (<?= count($poli) ?>)</a></li>
    <li><a href="/santri-belajar/public/admin/dokter">Kelola Dokter (<?= count($dokter) ?>)</a></li>
    <li><a href="/santri-belajar/public/admin/jadwal">Jadwal Dokter</a></li>
    <li><a href="/santri-belajar/public/admin/pasien">Database Pasien</a></li>
    <li><a href="/santri-belajar/public/admin/laporan">Laporan &amp; Analitik</a></li>
    <li><a href="/santri-belajar/public/admin/petugas">Pengguna &amp; Petugas (<?= count($staff) ?>)</a></li>
    <li><a href="/santri-belajar/public/admin/pengaturan">Pengaturan</a></li>
    <li><a href="/santri-belajar/public/admin/logout">Keluar</a></li>
</ul>
