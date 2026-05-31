<?php
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$staff = $_SESSION['staff'] ?? ['name' => 'Admin', 'role' => 'staff'];

$navOps = [
    ['admin',         'Dashboard', 'M4 4h7v7H4zM13 4h7v7h-7zM13 13h7v7h-7zM4 13h7v7H4z'],
    ['admin/antrean', 'Antrean',   'M12 3 2 8l10 5 10-5zM2 16l10 5 10-5M2 12l10 5 10-5'],
    ['admin/loket',   'Loket',     'M3 11v2a1 1 0 0 0 1 1h2l4 4V6L6 10H4a1 1 0 0 0-1 1ZM15 8a4 4 0 0 1 0 8'],
    ['admin/walkin',  'Walk-in',   'M12 5v14M5 12h14'],
];
$navData = [
    ['admin/poli',   'Poli',   'M6 3v6a4 4 0 0 0 8 0V3M5 3h2M13 3h2M10 13v3a5 5 0 0 0 10 0v-2M20 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z'],
    ['admin/dokter', 'Dokter', 'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM2 21a7 7 0 0 1 14 0M17 11a4 4 0 0 0 0-8M22 21a7 7 0 0 0-5-6.7'],
    ['admin/jadwal', 'Jadwal', 'M4 5h16v16H4zM4 9h16M8 3v4M16 3v4'],
    ['admin/pasien', 'Pasien', 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4 21a8 8 0 0 1 16 0'],
];

$isActive = function (string $path) use ($uri): bool {
    $full = BASE_URL . '/' . $path;
    if ($path === 'admin') return rtrim($uri, '/') === rtrim($full, '/');
    return str_starts_with($uri, $full);
};
$navItem = function (array $it) use ($isActive) {
    [$path, $label, $icon] = $it;
    $cls = 'admin-item' . ($isActive($path) ? ' on' : '');
    echo '<a class="' . $cls . '" href="' . BASE_URL . '/' . $path . '">';
    echo '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="' . $icon . '"/></svg>';
    echo '<span>' . htmlspecialchars($label) . '</span></a>';
};
$initial = strtoupper(substr(trim($staff['name'] ?? 'A'), 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SANTRI Admin' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="admin">
    <aside class="admin-side">
        <div class="admin-brand">
            <?php $logoSize = 38; include __DIR__ . '/_logo.php'; ?>
            <div class="admin-bname"><strong>SANTRI</strong><span><?= HOSPITAL_NAME ?></span></div>
        </div>

        <nav class="admin-nav">
            <div class="admin-sec">Operasional</div>
            <?php foreach ($navOps as $it) $navItem($it); ?>
            <div class="admin-sec">Master Data</div>
            <?php foreach ($navData as $it) $navItem($it); ?>
        </nav>

        <div class="admin-side-foot">
            <div class="admin-user">
                <div class="avatar"><?= htmlspecialchars($initial) ?></div>
                <div class="admin-user-info">
                    <strong><?= htmlspecialchars($staff['name'] ?? 'Admin') ?></strong>
                    <span><?= htmlspecialchars(ucfirst($staff['role'] ?? 'staff')) ?></span>
                </div>
            </div>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-top">
            <div>
                <h1 class="admin-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
                <?php if (!empty($subtitle)): ?><p class="admin-sub"><?= htmlspecialchars($subtitle) ?></p><?php endif; ?>
            </div>
            <div class="admin-top-right">
                <a class="btn btn-ghost" href="<?= BASE_URL ?>/admin/logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Keluar
                </a>
            </div>
        </header>

        <main class="admin-scroll">
            <div class="page">
                <?php if (!empty($_SESSION['flash'])): ?>
                    <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
                    <div class="alert alert-<?= ($f['kind'] ?? 'ok') === 'ok' ? 'success' : 'warning' ?>">
                        <?= htmlspecialchars($f['message'] ?? '') ?>
                    </div>
                <?php endif; ?>
                <?= $content ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>
