<?php $base = '/santri-belajar/public'; ?>

<h1>Pengguna & Petugas</h1>

<p><a href="<?= $base ?>/admin/petugas/create">Tambah Petugas</a></p>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Loket</th>
        <th>Shift</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach (($rows ?? []) as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['role'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['loket'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['shift'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
            <td>
                <a href="<?= $base ?>/admin/petugas/<?= $row['id'] ?>/edit">Edit</a>
                <form action="<?= $base ?>/admin/petugas/<?= $row['id'] ?>/delete" method="post">
                    <button type="submit" onclick="return confirm('Hapus data ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
