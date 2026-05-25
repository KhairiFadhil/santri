<?php /** @var array $user */ /** @var ?array $aktif */ ?>

<h1>Dashboard</h1>

<p>Halo, <strong><?= htmlspecialchars($user['name']) ?></strong>!</p>

<?php if ($aktif): ?>
    <h2>Antrean Aktif Anda</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>Nomor</th><td><strong><?= htmlspecialchars($aktif['ticket_code']) ?></strong></td></tr>
        <tr><th>Poli</th><td><?= htmlspecialchars($aktif['poli_name']) ?></td></tr>
        <tr><th>Dokter</th><td><?= htmlspecialchars($aktif['doctor_name']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= htmlspecialchars(format_tanggal_id($aktif['schedule_date'])) ?></td></tr>
        <tr><th>Status</th><td><strong><?= htmlspecialchars(strtoupper($aktif['status'])) ?></strong></td></tr>
    </table>
    <p><a href="/santri-belajar/public/antrean">Lihat Detail Status →</a></p>
<?php endif; ?>

<h2>Data Anda</h2>
<ul>
    <li>Nama: <?= htmlspecialchars($user['name']) ?></li>
    <li>Email: <?= htmlspecialchars($user['email']) ?></li>
    <li>NIK: <?= htmlspecialchars($user['nik']) ?></li>
</ul>

<h2>Menu</h2>
<ul>
    <?php if (!$aktif): ?>
        <li><a href="/santri-belajar/public/daftar">Daftar Antrean</a></li>
    <?php else: ?>
        <li><a href="/santri-belajar/public/antrean">Antrean Saya</a></li>
    <?php endif; ?>
    <li><a href="/santri-belajar/public/riwayat">Riwayat Antrean</a></li>
    <li><a href="/santri-belajar/public/profile">Profil</a></li>
    <li><a href="/santri-belajar/public/logout">Keluar</a></li>
</ul>
