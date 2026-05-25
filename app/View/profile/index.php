<?php /** @var array $user */ /** @var array $errors */ /** @var ?array $flash */ ?>

<h1>Profil Saya</h1>

<?php if ($flash): ?>
    <p style="color: green;"><strong><?= htmlspecialchars($flash['message']) ?></strong></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Data Diri</h2>
<form method="POST" action="/santri-belajar/public/profile">
    <p>
        <label>NIK:<br>
            <input type="text" value="<?= htmlspecialchars($user['nik']) ?>" disabled>
        </label>
    </p>
    <p>
        <label>Nama:<br>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </label>
    </p>
    <p>
        <label>Email:<br>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </label>
    </p>
    <p>
        <label>No. HP:<br>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </label>
    </p>
    <p>
        <label>Tanggal Lahir:<br>
            <input type="date" name="birth" value="<?= htmlspecialchars($user['birth'] ?? '') ?>">
        </label>
    </p>
    <p>
        <label>Jenis Kelamin:<br>
            <select name="gender">
                <option value="">-- pilih --</option>
                <option value="Laki-laki" <?= $user['gender']==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $user['gender']==='Perempuan'?'selected':'' ?>>Perempuan</option>
            </select>
        </label>
    </p>
    <p>
        <label>Alamat:<br>
            <textarea name="address" rows="3" cols="40"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </label>
    </p>
    <p>
        <button type="submit">Simpan</button>
    </p>
</form>

<h2>Ganti Password</h2>
<form method="POST" action="/santri-belajar/public/profile/password">
    <p>
        <label>Password Saat Ini:<br>
            <input type="password" name="current_password" required>
        </label>
    </p>
    <p>
        <label>Password Baru (min 8 karakter):<br>
            <input type="password" name="new_password" minlength="8" required>
        </label>
    </p>
    <p>
        <label>Konfirmasi Password Baru:<br>
            <input type="password" name="confirm_password" minlength="8" required>
        </label>
    </p>
    <p>
        <button type="submit">Ganti Password</button>
    </p>
</form>

<p><a href="/santri-belajar/public/dashboard">← Kembali ke Dashboard</a></p>
