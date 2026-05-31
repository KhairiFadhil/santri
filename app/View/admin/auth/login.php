<?php /** @var string $error */ /** @var string $email */ $base = BASE_URL; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">

<h1>Login Petugas</h1>
<p class="page-title-note">Masuk sebagai admin, petugas, atau dokter.</p>

<?php if (!empty($error)): ?>
    <p class="alert alert-danger"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="<?= $base ?>/admin/login">
        <p>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </p>

        <p>
            <label>Password</label>
            <input type="password" name="password" required>
        </p>

        <div class="form-actions">
            <button type="submit">Masuk</button>
            <a class="btn-secondary" href="<?= $base ?>/">Kembali</a>
        </div>
    </form>
</div>