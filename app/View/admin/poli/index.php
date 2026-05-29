<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1>Daftar Poli</h1>
        <p class="page-title-note">Kelola data poli dan status buka/tutup.</p>
    </div>
    <div class="section-actions">
        <a class="button" href="<?= $base ?>/admin/poli/create">Tambah Poli</a>
    </div>
</div>

<?php if (empty($rows)): ?>
    <div class="empty-state">Belum ada data poli.</div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Poli</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (($rows ?? []) as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><strong><?= htmlspecialchars($row['code'] ?? '') ?></strong></td>
                <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['sub'] ?? '') ?></td>
                <td><span class="badge <?= !empty($row['is_open']) ? 'badge-success' : 'badge-muted' ?>"><?= !empty($row['is_open']) ? 'Buka' : 'Tutup' ?></span></td>
                <td>
                    <div class="action-group">
                        <a href="<?= $base ?>/admin/poli/<?= $row['id'] ?>/edit">Edit</a>
                        <form action="<?= $base ?>/admin/poli/<?= $row['id'] ?>/delete" method="post">
                            <button type="submit" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>