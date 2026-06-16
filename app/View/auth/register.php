<?php /** @var array $errors */ /** @var array $form */ ?>

<div class="auth">

    <div class="auth-brand">
        <a class="auth-logo" href="<?= BASE_URL ?>/">
            <?php $logoSize = 42; $logoPlain = true; $logoColor = '#fff'; include __DIR__ . '/../layout/_logo.php'; ?>
            <span>SANTRI</span>
        </a>
        <div class="auth-brand-mid">
            <h2>Daftar sekali,<br>antre kapan saja.</h2>
            <p>Buat akun pasien untuk mengambil nomor antrean online dan memantau posisi antrean Anda.</p>
        </div>
        <div class="auth-brand-foot">&copy; <?= date('Y') ?> <?= HOSPITAL_NAME ?> &middot; SANTRI</div>
        <span class="auth-blob auth-blob-1"></span>
        <span class="auth-blob auth-blob-2"></span>
    </div>

    <div class="auth-form">
        <div class="auth-form-top">
            <a class="btn btn-ghost" href="<?= BASE_URL ?>/">&larr; Beranda</a>
        </div>
        <div class="auth-form-mid">
            <div class="auth-box auth-box-wide">
                <h1>Buat Akun Pasien</h1>
                <p class="auth-sub">Lengkapi data diri Anda untuk mendaftar.</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $err): ?>
                            <div><?= htmlspecialchars($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/register">
                    <label class="auth-label">Nama Lengkap</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($form['name']) ?>" placeholder="Nama sesuai KTP" required>

                    <div class="form-row">
                        <div>
                            <label class="auth-label">NIK</label>
                            <input type="text" name="nik" value="<?= htmlspecialchars($form['nik']) ?>" maxlength="16" placeholder="16 digit" required>
                        </div>
                        <div>
                            <label class="auth-label">No. HP</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($form['phone']) ?>" placeholder="08xxx">
                        </div>
                    </div>

                    <label class="auth-label">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($form['email']) ?>" placeholder="nama@email.com" required>

                    <div class="form-row">
                        <div>
                            <label class="auth-label">Jenis Kelamin</label>
                            <select name="gender">
                                <option value="">-- pilih --</option>
                                <option value="Laki-laki" <?= $form['gender']==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= $form['gender']==='Perempuan'?'selected':'' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="auth-label">Penjamin</label>
                            <select name="insurance_type">
                                <option value="BPJS"     <?= $form['insurance_type']==='BPJS'?'selected':'' ?>>BPJS</option>
                                <option value="Asuransi" <?= $form['insurance_type']==='Asuransi'?'selected':'' ?>>Asuransi Swasta</option>
                                <option value="Umum"     <?= $form['insurance_type']==='Umum'?'selected':'' ?>>Umum</option>
                            </select>
                        </div>
                    </div>

                    <label class="auth-label">Kata Sandi</label>
                    <input type="password" name="password" minlength="8" placeholder="Minimal 8 karakter" required>

                    <button class="btn btn-primary btn-lg btn-block" type="submit" style="margin-top:8px">Daftar</button>
                </form>

                <div class="auth-alt">
                    Sudah punya akun? <a href="<?= BASE_URL ?>/login">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
