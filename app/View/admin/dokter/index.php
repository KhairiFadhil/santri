<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1>Daftar Dokter</h1>
        <p class="page-title-note">Kelola data dokter dan poli tempat praktik.</p>
    </div>
    <div class="section-actions">
        <a class="btn btn-primary" href="<?= $base ?>/admin/dokter/create">Tambah Dokter</a>
    </div>
</div>

<?php if (empty($rows)): ?>
    <div class="empty-state">Belum ada data dokter.</div>
<?php else: ?>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Dokter</th>
            <th>Spesialis</th>
            <th>Poli</th>
            <th>Status</th>
            <th>Akun Login</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (($rows ?? []) as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><strong><?= htmlspecialchars($row['name'] ?? '') ?></strong></td>
                <td><?= htmlspecialchars($row['specialization'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['poli_name'] ?? '') ?></td>
                <td><span class="badge <?= !empty($row['is_active']) ? 'badge-success' : 'badge-muted' ?>"><?= !empty($row['is_active']) ? 'Aktif' : 'Tidak Aktif' ?></span></td>
                <td>
                    <?php if (!empty($akun[$row['id']])): ?>
                        <?= htmlspecialchars($akun[$row['id']]['email']) ?><br>
                        Pass: <?= htmlspecialchars($akun[$row['id']]['pw'] ?? '-') ?>
                    <?php else: ?>
                        <form action="<?= $base ?>/admin/dokter/<?= $row['id'] ?>/akun" method="post">
                            <button type="submit" onclick="return confirm('Buat akun untuk dokter ini?')" class="btn btn-ghost">Generate Akun</button>
                        </form>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-group">
                        <a href="<?= $base ?>/admin/dokter/<?= $row['id'] ?>/edit" class="btn btn-primary">Edit</a>
                        <form action="<?= $base ?>/admin/dokter/<?= $row['id'] ?>/delete" method="post">
                            <button type="submit" onclick="return confirm('Hapus data ini?')" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
