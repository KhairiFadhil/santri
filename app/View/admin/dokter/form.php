<?php
$base = BASE_URL;
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/dokter/' . ($form['id'] ?? '') : $base . '/admin/dokter';
?>

<div class="section-header">
    <div>
        <h1><?= $isEdit ? 'Edit Dokter' : 'Tambah Dokter' ?></h1>
        <p class="page-title-note">Lengkapi data dokter dan status praktik.</p>
    </div>
    <div class="section-actions">
        <a class="btn-secondary" href="<?= $base ?>/admin/dokter">Kembali</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <ul class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div class="form-card">
    <form action="<?= $action ?>" method="post">
        <p>
            <label>Poli</label>
            <select name="poli_id" required>
                <option value="">Pilih Poli</option>
                <?php foreach (($poli ?? []) as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (string)($form['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <div class="form-row">
            <p>
                <label>Nama Dokter</label>
                <input type="text" name="name" value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
            </p>
            <p>
                <label>Spesialis</label>
                <input type="text" name="specialization" value="<?= htmlspecialchars($form['specialization'] ?? '') ?>">
            </p>
        </div>
        <p>
            <label>Foto</label>
            <input type="text" name="photo" value="<?= htmlspecialchars($form['photo'] ?? '') ?>">
        </p>
        <p>
            <label>
                <input type="checkbox" name="is_active" value="1" <?= !empty($form['is_active']) ? 'checked' : '' ?>>
                Aktif
            </label>
        </p>
        <div class="form-actions">
            <button type="submit">Simpan</button>
            <a class="btn-secondary" href="<?= $base ?>/admin/dokter">Kembali</a>
        </div>
    </form>
</div>