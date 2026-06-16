<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1 style="color: var(--text);">Database Pasien</h1>
        <p class="page-title-note">Cari dan kelola data pasien terdaftar.</p>
    </div>
</div>

<div class="filter-box">
    <form method="get" action="<?= $base ?>/admin/pasien">
        <label>Cari Berdasarkan
            <select name="filter" class="form-control">
                <option value="nama"  <?= ($filter ?? 'nama') === 'nama'  ? 'selected' : '' ?>>Nama</option>
                <option value="nik"   <?= ($filter ?? '') === 'nik'   ? 'selected' : '' ?>>NIK</option>
                <option value="email" <?= ($filter ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                <option value="hp"    <?= ($filter ?? '') === 'hp'    ? 'selected' : '' ?>>No HP</option>
            </select>
        </label>
        <label>Kata Kunci
            <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Ketik kata kunci...">
        </label>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>

<?php if (empty($rows)): ?>
    <div class="card empty-state">Data pasien tidak ditemukan.</div>
<?php else: ?>
<div class="card table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($rows ?? []) as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($row['nik'] ?? '') ?></td>
                    <td><strong><?= htmlspecialchars($row['name'] ?? '') ?></strong></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['gender'] ?? '') ?></td>
                    <td>
                        <form class="inline-form" action="<?= $base ?>/admin/pasien/<?= $row['id'] ?>/delete" method="post">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus data pasien ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
