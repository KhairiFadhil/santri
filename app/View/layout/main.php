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
    <div class="util-bar">
        <div class="util-in">
            <div class="util-left">
                <span class="util-dot"></span>
                Situs Resmi RS Medicaria
            </div>
            <div class="util-right">
                <span>(021) 5550-1234</span>
                <span class="util-sep"></span>
                <a href="<?= BASE_URL ?>/#lokasi">Lokasi</a>
            </div>
        </div>
    </div>
    <header class="beranda">
        <div class="beranda-in">
            <a class="beranda-brand" href="<?= BASE_URL ?>/">
                <?php $logoSize = 38; $logoPlain = true; include __DIR__ . '/_logo.php'; ?>
                <div class="beranda-bname">
                    <strong>SANTRI</strong>
                    <span>RS Medicaria</span>
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
    <footer class="foot" id="lokasi">
        <div class="foot-hair"></div>

        
        <div class="foot-top">
            <div>
                <div class="foot-brand-row">
                    <?php $logoSize = 42; include __DIR__ . '/_logo.php'; ?>
                    <div>
                        <div class="foot-brand-name">SANTRI</div>
                        <div class="foot-brand-sub">RS Medicaria</div>
                    </div>
                </div>
                <p class="foot-desc">Sistem Antrian Rumah Sakit terintegrasi yang dikelola RS Medicaria untuk pelayanan kesehatan yang lebih cepat, tertib, dan nyaman bagi seluruh pasien.</p>
                <div class="foot-contacts">
                    <div class="foot-contact">
                        <div class="foot-contact-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-5.2-7-11a7 7 0 0 1 14 0c0 5.8-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg></div>
                        <div><div class="foot-contact-l">Alamat</div><div class="foot-contact-v">Jl. Merpati Raya No. 14, Jakarta</div></div>
                    </div>
                    <div class="foot-contact">
                        <div class="foot-contact-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h3l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V20a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1Z"/></svg></div>
                        <div><div class="foot-contact-l">Halo SANTRI</div><div class="foot-contact-v">(021) 5550-1234 · 24 jam, hari kerja</div></div>
                    </div>
                    <div class="foot-contact">
                        <div class="foot-contact-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
                        <div><div class="foot-contact-l">Email Resmi</div><div class="foot-contact-v">halo@medicaria.id</div></div>
                    </div>
                </div>
            </div>

            <div class="foot-col">
                <h4>Layanan</h4>
                <div class="foot-links">
                    <a class="foot-link" href="<?= BASE_URL ?>/daftar">Daftar Antrean</a>
                    <a class="foot-link" href="<?= BASE_URL ?>/#poli">Poliklinik</a>
                    <a class="foot-link" href="<?= BASE_URL ?>/#live">Antrean Live</a>
                    <a class="foot-link" href="<?= BASE_URL ?>/riwayat">Riwayat Kunjungan</a>
                </div>
            </div>
        </div>

        
        <div class="foot-botwrap">
            <div class="foot-bot">
                <div class="foot-copy">&copy; <?= date('Y') ?> <strong>RS Medicaria</strong> · SANTRI — Sistem Antrian Rumah Sakit. Hak cipta dilindungi.</div>
            </div>
        </div>
    </footer>
    <?php endif; ?>
</body>
</html>
