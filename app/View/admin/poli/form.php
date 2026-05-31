<?php
$base = BASE_URL;
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/poli/' . ($form['id'] ?? '') : $base . '/admin/poli';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">

<div class="flex justify-between items-center mb-3">
    <div>
        <h1 style="margin: 0; font-size: 24px;"><?= $isEdit ? 'Edit Poli' : 'Tambah Poli' ?></h1>
        <p style="margin: 4px 0 0; color: var(--teks-3);">Isi data poli sesuai kebutuhan layanan rumah sakit.</p>
    </div>
    <div>
        <a class="btn btn-danger" href="<?= $base ?>/admin/poli">Kembali</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger mb-3">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <form action="<?= $action ?>" method="post">        
        <div class="flex gap-2 mb-2">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Kode Poli</label>
                <input type="text" name="code" class="form-control" maxlength="3" value="<?= htmlspecialchars($form['code'] ?? '') ?>" required>
            </div>
            <div class="form-group" style="flex: 2;">
                <label class="form-label">Nama Poli</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
            </div>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Keterangan</label>
            <textarea name="sub" class="form-control" rows="3"><?= htmlspecialchars($form['sub'] ?? '') ?></textarea>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Icon</label>
            <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($form['icon'] ?? 'Stethoscope') ?>">
        </div>
        <div class="form-group mb-3">
            <label class="flex items-center gap-1" style="cursor: pointer; font-weight: 600; color: var(--teks-2);">
                <input type="checkbox" name="is_open" value="1" <?= !empty($form['is_open']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--accent);">
                Poli Buka (Aktif)
            </label>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a class="btn btn-danger" href="<?= $base ?>/admin/poli">Batal</a>
        </div>        
    </form>
</div>