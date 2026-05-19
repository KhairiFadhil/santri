<?php /** @var array $user */ ?>

<h1>Dashboard</h1>

<p>Halo, <strong><?= htmlspecialchars($user['name']) ?></strong>!</p>

<h2>Data Anda</h2>
<ul>
    <li>Nama: <?= htmlspecialchars($user['name']) ?></li>
    <li>Email: <?= htmlspecialchars($user['email']) ?></li>
    <li>NIK: <?= htmlspecialchars($user['nik']) ?></li>
</ul>

<p><a href="/santri-belajar/public/logout">Keluar</a></p>
