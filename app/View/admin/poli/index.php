<?php $base = '/santri-belajar/public'; ?>

<h1>Daftar Poli</h1>

<p><a href="<?= $base ?>/admin/poli/create">Tambah Poli</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Poli</th>
        <th>Keterangan</th>
        <th>Kapasitas</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach (($rows ?? []) as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['sub'] ?? '') ?></td>
            <td><?= htmlspecialchars((string)($row['capacity_daily'] ?? '')) ?></td>
            <td><?= !empty($row['is_open']) ? 'Buka' : 'Tutup' ?></td>
            <td>
                <a href="<?= $base ?>/admin/poli/<?= $row['id'] ?>/edit">Edit</a>
                <form action="<?= $base ?>/admin/poli/<?= $row['id'] ?>/delete" method="post">
                    <button type="submit" onclick="return confirm('Hapus data ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
