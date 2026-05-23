<?php $base = '/santri-belajar/public'; ?>

<h1>Daftar Dokter</h1>

<p><a href="<?= $base ?>/admin/dokter/create">Tambah Dokter</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama Dokter</th>
        <th>Spesialis</th>
        <th>Poli</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach (($rows ?? []) as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['specialization'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
            <td><?= !empty($row['is_active']) ? 'Aktif' : 'Tidak Aktif' ?></td>
            <td>
                <a href="<?= $base ?>/admin/dokter/<?= $row['id'] ?>/edit">Edit</a>
                <form action="<?= $base ?>/admin/dokter/<?= $row['id'] ?>/delete" method="post">
                    <button type="submit" onclick="return confirm('Hapus data ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
