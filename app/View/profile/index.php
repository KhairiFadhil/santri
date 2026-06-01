<?php /** @var array $user */ /** @var array $errors */ /** @var ?array $flash */ ?>

<div class="user-page">
    <div class="section-header user-section-head">
        <div>
            <h1>Profil Saya</h1>
            <p class="page-title-note">Perbarui data diri dan kata sandi akun pasien.</p>
        </div>
        <a class="btn btn-ghost" href="<?= BASE_URL ?>/dashboard">← Dashboard</a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="user-grid-2">
        <section class="card card-pad">
            <div class="card-title">Data Diri</div>
            <form method="POST" action="<?= BASE_URL ?>/profile">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NIK</label>
                        <input class="form-control" type="text" value="<?= htmlspecialchars($user['nik']) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <input class="form-control" type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input class="form-control" type="date" name="birth" value="<?= htmlspecialchars($user['birth'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-control" name="gender">
                            <option value="">-- pilih --</option>
                            <option value="Laki-laki" <?= $user['gender']==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $user['gender']==='Perempuan'?'selected':'' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Simpan Data</button>
            </form>
        </section>

        <section class="card card-pad">
            <div class="card-title">Ganti Password</div>
            <form method="POST" action="<?= BASE_URL ?>/profile/password">
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input class="form-control" type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru <span class="muted">(min 8 karakter)</span></label>
                    <input class="form-control" type="password" name="new_password" minlength="8" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input class="form-control" type="password" name="confirm_password" minlength="8" required>
                </div>
                <button class="btn btn-secondary" type="submit">Ganti Password</button>
            </form>
        </section>
    </div>
</div>