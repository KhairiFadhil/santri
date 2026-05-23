<?php
$base = '/santri-belajar/public';
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit ? $base . '/admin/petugas/' . ($form['id'] ?? '') : $base . '/admin/petugas';
?>

<h1><?= $isEdit ? 'Edit Petugas' : 'Tambah Petugas' ?></h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= $action ?>" method="post">
    <p>
        <label>Nama</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($form['name'] ?? '') ?>" required>
    </p>
    <p>
        <label>Email</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($form['email'] ?? '') ?>" required>
    </p>
    <p>
        <label>Password <?= $isEdit ? '(kosongkan jika tidak diganti)' : '' ?></label><br>
        <input type="password" name="password" <?= $isEdit ? '' : 'required' ?>>
    </p>
    <p>
        <label>Role</label><br>
        <select name="role">
            <?php foreach (($roles ?? []) as $role): ?>
                <option value="<?= $role ?>" <?= ($form['role'] ?? '') === $role ? 'selected' : '' ?>>
                    <?= htmlspecialchars($role) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Loket</label><br>
        <input type="text" name="loket" value="<?= htmlspecialchars($form['loket'] ?? '') ?>">
    </p>
    <p>
        <label>Shift</label><br>
        <input type="text" name="shift" value="<?= htmlspecialchars($form['shift'] ?? '') ?>">
    </p>
    <p>
        <label>Status</label><br>
        <select name="status">
            <?php foreach (($statuses ?? []) as $status): ?>
                <option value="<?= $status ?>" <?= ($form['status'] ?? '') === $status ? 'selected' : '' ?>>
                    <?= htmlspecialchars($status) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <button type="submit">Simpan</button>
    <a href="<?= $base ?>/admin/petugas">Kembali</a>
</form>
