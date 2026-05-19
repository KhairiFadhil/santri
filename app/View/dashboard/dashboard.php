<?php /** @var array $user */ ?>

<h1>Dashboard</h1>

<p>Halo, <strong><?= htmlspecialchars($user['name']) ?></strong>!</p>

<h2>Data Anda</h2>
<ul>
    <li>Nama: <?= htmlspecialchars($user['name']) ?></li>
    <li>Email: <?= htmlspecialchars($user['email']) ?></li>
    <li>NIK: <?= htmlspecialchars($user['nik']) ?></li>
</ul>

<h2>Menu</h2>
<ul>
    <li><a href="/santri-belajar/public/daftar">Daftar Antrean</a></li>
    <li><a href="/santri-belajar/public/antrean">Antrean Saya</a></li>
    <li><a href="/santri-belajar/public/riwayat">Riwayat Antrean</a></li>
    <li><a href="/santri-belajar/public/profile">Profil</a></li>
    <li><a href="/santri-belajar/public/logout">Keluar</a></li>
</ul>
