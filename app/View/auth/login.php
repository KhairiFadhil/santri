<?php /** @var string $error */ /** @var string $email */ ?>

<h1>Login</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['flash'])): ?>
    <p style="color: green;"><?= htmlspecialchars($_SESSION['flash']['message']) ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<form method="POST" action="/santri-belajar/public/login">
    <p>
        <label>Email:<br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </label>
    </p>

    <p>
        <label>Password:<br>
        <input type="password" name="password" required>
        </label>
    </p>

    <p>
        <button type="submit">Masuk</button>
    </p>
</form>

<p>Belum punya akun? <a href="/santri-belajar/public/register">Daftar di sini</a></p>
