<?php
$base = '/santri-belajar/public';
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/poli/' . ($form['id'] ?? '') : $base . '/admin/poli';
?>

<h1><?= $isEdit ? 'Edit Poli' : 'Tambah Poli' ?></h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= $action ?>" method="post">
    <p>
        <label>Kode Poli</label><br>
        <input type="text" name="code" maxlength="3" value="<?= htmlspecialchars($form['code'] ?? '') ?>" required>
    </p>
    <p>
        <label>Nama Poli</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
    </p>
    <p>
        <label>Keterangan</label><br>
        <textarea name="sub"><?= htmlspecialchars($form['sub'] ?? '') ?></textarea>
    </p>
    <p>
        <label>Icon</label><br>
        <input type="text" name="icon" value="<?= htmlspecialchars($form['icon'] ?? 'Stethoscope') ?>">
    </p>
    <p>
        <label>
            <input type="checkbox" name="is_open" value="1" <?= !empty($form['is_open']) ? 'checked' : '' ?>>
            Poli buka
        </label>
    </p>
    <button type="submit">Simpan</button>
    <a href="<?= $base ?>/admin/poli">Kembali</a>
</form>
