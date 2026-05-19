<?php /** @var array $errors */ /** @var array $form */ ?>

<h1>Daftar Akun</h1>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="/santri-belajar/public/register">
    <p>
        <label>Nama lengkap:<br>
        <input type="text" name="name" value="<?= htmlspecialchars($form['name']) ?>" required>
        </label>
    </p>

    <p>
        <label>NIK (16 digit):<br>
        <input type="text" name="nik" value="<?= htmlspecialchars($form['nik']) ?>" maxlength="16" required>
        </label>
    </p>

    <p>
        <label>Email:<br>
        <input type="email" name="email" value="<?= htmlspecialchars($form['email']) ?>" required>
        </label>
    </p>

    <p>
        <label>No. HP:<br>
        <input type="text" name="phone" value="<?= htmlspecialchars($form['phone']) ?>">
        </label>
    </p>

    <p>
        <label>Jenis kelamin:<br>
        <select name="gender">
            <option value="">-- pilih --</option>
            <option value="Laki-laki" <?= $form['gender']==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $form['gender']==='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
        </label>
    </p>

    <p>
        <label>Asuransi:<br>
        <select name="insurance_type">
            <option value="BPJS"     <?= $form['insurance_type']==='BPJS'?'selected':'' ?>>BPJS</option>
            <option value="Asuransi" <?= $form['insurance_type']==='Asuransi'?'selected':'' ?>>Asuransi Swasta</option>
            <option value="Umum"     <?= $form['insurance_type']==='Umum'?'selected':'' ?>>Umum</option>
        </select>
        </label>
    </p>

    <p>
        <label>Password (min 8 karakter):<br>
        <input type="password" name="password" minlength="8" required>
        </label>
    </p>

    <p>
        <button type="submit">Daftar</button>
    </p>
</form>

<p>Sudah punya akun? <a href="/santri-belajar/public/login">Masuk di sini</a></p>
