<?php
$base = BASE_URL;
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/poli/' . ($form['id'] ?? '') : $base . '/admin/poli';
?>

<div class="section-header">
    <div>
        <h1><?= $isEdit ? 'Edit Poli' : 'Tambah Poli' ?></h1>
        <p class="page-title-note">Isi data poli sesuai kebutuhan layanan rumah sakit.</p>
    </div>
    <div class="section-actions">
        <a class="btn-secondary" href="<?= $base ?>/admin/poli">Kembali</a>
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
        <div class="form-row">
            <p>
                <label>Kode Poli</label>
                <input type="text" name="code" maxlength="3" value="<?= htmlspecialchars($form['code'] ?? '') ?>" required>
            </p>
            <p>
                <label>Nama Poli</label>
                <input type="text" name="name" value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
            </p>
        </div>
        <p>
            <label>Keterangan</label>
            <textarea name="sub"><?= htmlspecialchars($form['sub'] ?? '') ?></textarea>
        </p>
        <p>
            <label>Icon</label>
            <input type="text" name="icon" value="<?= htmlspecialchars($form['icon'] ?? 'Stethoscope') ?>">
        </p>
        <p>
            <label>
                <input type="checkbox" name="is_open" value="1" <?= !empty($form['is_open']) ? 'checked' : '' ?>>
                Poli buka
            </label>
        </p>
        <div class="form-actions">
            <button type="submit">Simpan</button>
            <a class="btn-secondary" href="<?= $base ?>/admin/poli">Kembali</a>
        </div>
    </form>
</div>