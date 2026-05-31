<?php
$base = BASE_URL;
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/dokter/' . ($form['id'] ?? '') : $base . '/admin/dokter';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">

<div class="flex justify-between items-center mb-3">
    <div>
        <h1 style="margin: 0; font-size: 24px;"><?= $isEdit ? 'Edit Dokter' : 'Tambah Dokter' ?></h1>
        <p style="margin: 4px 0 0; color: var(--teks-3);">Lengkapi data dokter dan status praktik.</p>
    </div>
    <div>
        <a class="btn btn-danger" href="<?= $base ?>/admin/dokter">Kembali</a>
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
        <div class="form-group mb-2">
            <label class="form-label">Poli</label>
            <select name="poli_id" class="form-control" required>
                <option value="">Pilih Poli</option>
                <?php foreach (($poli ?? []) as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= (string)($form['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex gap-2 mb-2">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Nama Dokter</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($form['name'] ?? '') ?>" placeholder="Contoh: dr. Budi Santoso" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Spesialis</label>
                <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($form['specialization'] ?? '') ?>" placeholder="Contoh: Spesialis Anak">
            </div>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Nama File Foto</label>
            <input type="text" name="photo" class="form-control" value="<?= htmlspecialchars($form['photo'] ?? '') ?>" placeholder="Contoh: dokter-budi.jpg">
        </div>
        <div class="form-group mb-3">
            <label class="flex items-center gap-1" style="cursor: pointer; font-weight: 600; color: var(--teks-2);">
                <input type="checkbox" name="is_active" value="1" <?= !empty($form['is_active']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: var(--accent);">
                Dokter Aktif (Bisa Praktik)
            </label>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a class="btn btn-danger" href="<?= $base ?>/admin/dokter">Batal</a>
        </div>        
    </form>
</div>