<?php
$base = '/santri-belajar/public';
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/dokter/' . ($form['id'] ?? '') : $base . '/admin/dokter';
?>

<h1><?= $isEdit ? 'Edit Dokter' : 'Tambah Dokter' ?></h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= $action ?>" method="post">
    <p>
        <label>Poli</label><br>
        <select name="poli_id" required>
            <option value="">Pilih Poli</option>
            <?php foreach (($poli ?? []) as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (string)($form['poli_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Nama Dokter</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
    </p>
    <p>
        <label>Spesialis</label><br>
        <input type="text" name="specialization" value="<?= htmlspecialchars($form['specialization'] ?? '') ?>">
    </p>
    <p>
        <label>Foto</label><br>
        <input type="text" name="photo" value="<?= htmlspecialchars($form['photo'] ?? '') ?>">
    </p>
    <p>
        <label>
            <input type="checkbox" name="is_active" value="1" <?= !empty($form['is_active']) ? 'checked' : '' ?>>
            Aktif
        </label>
    </p>
    <button type="submit">Simpan</button>
    <a href="<?= $base ?>/admin/dokter">Kembali</a>
</form>
