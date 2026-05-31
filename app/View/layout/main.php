<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SANTRI' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <a href="<?= BASE_URL ?>/" class="brand">
                <span class="brand-mark">S</span>
                <span>SANTRI<small>Sistem Antrian RS</small></span>
            </a>
            <div class="nav-links">
                <a href="<?= BASE_URL ?>/">Beranda</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
                    <a href="<?= BASE_URL ?>/daftar">Daftar Antrean</a>
                    <a href="<?= BASE_URL ?>/antrean">Antrean Saya</a>
                    <a href="<?= BASE_URL ?>/riwayat">Riwayat</a>
                    <a href="<?= BASE_URL ?>/profile">Profil</a>
                    <a href="<?= BASE_URL ?>/logout">Keluar</a>
                <?php elseif (isset($_SESSION['staff'])): ?>
                    <?php if (($_SESSION['staff']['role'] ?? '') === 'dokter'): ?>
                        <a href="<?= BASE_URL ?>/dokter/loket">Loket Saya</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/admin">Admin</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/admin/logout">Keluar</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login">Masuk</a>
                    <a href="<?= BASE_URL ?>/register">Daftar Akun</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="page-shell">
        <?= $content ?>
    </main>

    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> RS Medicaria &middot; SANTRI</p>
    </footer>
</body>
</html>
