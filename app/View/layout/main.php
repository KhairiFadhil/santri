<?php
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$isHome = rtrim($uri, '/') === rtrim(BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SANTRI' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <header class="beranda">
        <div class="beranda-in">
            <a class="beranda-brand" href="<?= BASE_URL ?>/">
                <?php $logoSize = 38; $logoPlain = true; include __DIR__ . '/_logo.php'; ?>
                <div class="beranda-bname">
                    <strong>SANTRI</strong>
                    <span><?= HOSPITAL_NAME ?></span>
                </div>
            </a>

            <nav class="beranda-nav">
                <a class="beranda-link<?= $isHome ? ' on' : '' ?>" href="<?= BASE_URL ?>/">Beranda</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a class="beranda-link" href="<?= BASE_URL ?>/daftar">Daftar Antrean</a>
                    <a class="beranda-link" href="<?= BASE_URL ?>/antrean">Antrean Saya</a>
                    <a class="beranda-link" href="<?= BASE_URL ?>/riwayat">Riwayat</a>
                <?php endif; ?>
            </nav>

            <div class="beranda-cta">
                <?php if (isset($_SESSION['user'])): ?>
                    <a class="btn btn-ghost" href="<?= BASE_URL ?>/profile">Profil</a>
                    <a class="btn btn-ghost" href="<?= BASE_URL ?>/logout">Keluar</a>
                    <a class="btn btn-primary" href="<?= BASE_URL ?>/daftar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16v3a2 2 0 0 0 0 4v3H4v-3a2 2 0 0 0 0-4V6ZM12 7v10"/></svg>
                        Ambil Antrean
                    </a>
                <?php elseif (isset($_SESSION['staff'])): ?>
                    <?php if (($_SESSION['staff']['role'] ?? '') === 'dokter'): ?>
                        <a class="btn btn-primary" href="<?= BASE_URL ?>/dokter/loket">Loket Saya</a>
                    <?php else: ?>
                        <a class="btn btn-primary" href="<?= BASE_URL ?>/admin">Dashboard Admin</a>
                    <?php endif; ?>
                    <a class="btn btn-ghost" href="<?= BASE_URL ?>/admin/logout">Keluar</a>
                <?php else: ?>
                    <a class="btn btn-ghost" href="<?= BASE_URL ?>/login">Masuk</a>
                    <a class="btn btn-primary" href="<?= BASE_URL ?>/register">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16v3a2 2 0 0 0 0 4v3H4v-3a2 2 0 0 0 0-4V6ZM12 7v10"/></svg>
                        Ambil Antrean
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="beranda-wrap">
            <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <div class="alert alert-<?= ($f['kind'] ?? 'ok') === 'ok' ? 'success' : 'warning' ?>">
                <?= htmlspecialchars($f['message'] ?? '') ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($isHome): ?>
        <main><?= $content ?></main>
    <?php else: ?>
        <main class="beranda-wrap"><?= $content ?></main>
    <?php endif; ?>

    <?php if ($isHome): ?>
    <footer class="ft">
        <div class="ft-in">
            <div>
                <div class="ft-brand">
                    <?php $logoSize = 40; $logoPlain = true; $logoColor = '#fff'; include __DIR__ . '/_logo.php'; ?>
                    <span class="ft-bname">SANTRI</span>
                </div>
                <p class="ft-desc">Sistem antrian rumah sakit terintegrasi. Daftar online, pantau antrean real-time, datang tepat waktu.</p>
            </div>

            <div class="ft-col">
                <h4>Layanan</h4>
                <a href="<?= BASE_URL ?>/daftar">Daftar Antrean</a>
                <a href="<?= BASE_URL ?>/">Antrean Live</a>
            </div>

            <div class="ft-col">
                <h4>Kontak</h4>
                <span><?= HOSPITAL_PHONE ?></span>
                <span><?= HOSPITAL_EMAIL ?></span>
                <span><?= HOSPITAL_ADDRESS ?></span>
            </div>
        </div>
        <div class="ft-bottom">
            &copy; <?= date('Y') ?> <?= HOSPITAL_NAME ?> &middot; SANTRI — Sistem Antrian Rumah Sakit
        </div>
    </footer>
    <?php endif; ?>
</body>
</html>
