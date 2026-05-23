<?php $base = '/santri-belajar/public'; ?>

<h1>Database Pasien</h1>

<form method="get" action="<?= $base ?>/admin/pasien">
    <p>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari pasien">
        <button type="submit">Cari</button>
    </p>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Email</th>
        <th>No HP</th>
        <th>Jenis Kelamin</th>
        <th>Aksi</th>
    </tr>
    <?php foreach (($rows ?? []) as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['nik'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
            <td>
                <form action="<?= $base ?>/admin/pasien/<?= $row['id'] ?>/delete" method="post">
                    <button type="submit" onclick="return confirm('Hapus data pasien ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
