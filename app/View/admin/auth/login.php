<?php /** @var string $error */ /** @var string $email */ ?>

<h1>Login Petugas</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/santri-belajar/public/admin/login">
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
