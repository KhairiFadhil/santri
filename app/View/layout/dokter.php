<?php
$staff = $_SESSION['staff'] ?? ['name' => 'Dokter'];
$initial = strtoupper(substr(trim($staff['name'] ?? 'D'), 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SANTRI Dokter' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="dokter">
    <header class="dokter-top">
        <div class="dokter-brand">
            <?php $logoSize = 36; include __DIR__ . '/_logo.php'; ?>
            <div class="dokter-bname"><strong>SANTRI</strong><span>Loket Dokter</span></div>
        </div>
        <div class="dokter-doc">
            <div class="dokter-doc-info">
                <strong><?= htmlspecialchars($staff['name'] ?? 'Dokter') ?></strong>
                <span><?= htmlspecialchars($subtitle ?? 'Loket') ?></span>
            </div>
            <div class="avatar"><?= htmlspecialchars($initial) ?></div>
            <a class="admin-icon" href="<?= BASE_URL ?>/admin/logout" title="Keluar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            </a>
        </div>
    </header>

    <main class="dokter-scroll">
        <?php if (!empty($_SESSION['flash'])): ?>
            <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <div class="alert alert-<?= ($f['kind'] ?? 'success') === 'warn' ? 'warning' : 'success' ?>">
                <?= htmlspecialchars($f['message'] ?? '') ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>
</div>
</body>
</html>
