<?php $base = '/santri-belajar/public'; ?>

<h1>Pengaturan</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= $base ?>/admin/pengaturan" method="post">
    <table border="1" cellpadding="5">
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        <?php foreach (($settings ?? []) as $key => $value): ?>
            <tr>
                <td><?= htmlspecialchars($key) ?></td>
                <td>
                    <input type="text" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Tambah Pengaturan Baru</h2>
    <p>
        <label>Key</label><br>
        <input type="text" name="nama_rs">
    </p>
    <p>
        <label>Value</label><br>
        <input type="text" name="alamat_rs">
    </p>

    <button type="submit">Simpan</button>
</form>
