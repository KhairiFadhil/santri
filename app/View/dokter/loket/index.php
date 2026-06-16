<?php
$base = defined('BASE_URL') ? BASE_URL : '/santri-belajar/public';
$today = function_exists('format_tanggal_id') ? format_tanggal_id(date('Y-m-d')) : date('l, d F Y');

$e = static fn($value) => htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');

$statusLabel = static function (?string $status): string {
    return match ($status) {
        'wait' => 'Menunggu',
        'call' => 'Dipanggil',
        'progress' => 'Diperiksa',
        'done' => 'Selesai',
        'skip' => 'Dilewati',
        'cancel' => 'Batal',
        default => $status ?: '-',
    };
};

$badge = static function (?string $status) use ($statusLabel, $e): string {
    $status = $status ?: 'wait';
    return '<span class="badge ' . $e($status) . '"><i></i>' . $e($statusLabel($status)) . '</span>';
};

$icon = static function (string $name, int $size = 18): string {
    $paths = [
        'clock'     => 'M12 8v4l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'megaphone' => 'M3 11v2a2 2 0 0 0 2 2h2l4 4v-4l8-3V8l-8-3v10M7 15V9',
        'check'     => 'M20 6 9 17l-5-5',
        'x'         => 'M18 6 6 18M6 6l12 12',
        'play'      => 'M8 5v14l11-7L8 5Z',
        'next'      => 'M5 5l8 7-8 7V5Zm10 0h3v14h-3V5Z',
        'logout'    => 'M10 17l5-5-5-5M15 12H3M21 19V5a2 2 0 0 0-2-2h-6M13 21h6a2 2 0 0 0 2-2',
        'user'      => 'M20 21a8 8 0 0 0-16 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z',
        'pulse'     => 'M3 12h4l3-8 4 16 3-8h4',
        'calendar'  => 'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z',
        'shield'    => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z',
    ];
    $path = $paths[$name] ?? $paths['user'];
    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $path . '"></path></svg>';
};

$initial = static function (string $name): string {
    $words = explode(' ', trim(str_replace(['dr.', 'drg.'], '', strtolower($name))));
    $chars = '';
    foreach ($words as $w) {
        if ($w !== '') $chars .= strtoupper(substr($w, 0, 1));
        if (strlen($chars) >= 2) break;
    }
    return $chars ?: 'DR';
};

$doneOnly = array_values(array_filter($selesai ?? [], static fn($row) => ($row['status'] ?? '') === 'done'));
$skippedOnly = array_values(array_filter($selesai ?? [], static fn($row) => ($row['status'] ?? '') === 'skip'));
$avgMinutes = defined('ANTRIAN_PERORANG') ? ANTRIAN_PERORANG * 3 : 9;
$firstWaiting = !empty($waiting) ? $waiting[0] : null;
$doctorName = $dokter['name'] ?? 'Dokter';
$poliName = $dokter['poli_name'] ?? 'Poli';
$specialization = $dokter['specialization'] ?? 'Dokter';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loket Dokter — SANTRI</title>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
    <style>
        a { color: inherit; text-decoration: none; }
        .doctor-app { min-height: 100vh; background: var(--bg); display: flex; flex-direction: column; }
        .doctor-topbar {
            height: 70px; flex-shrink: 0; background: rgba(255,255,255,.92);
            backdrop-filter: blur(10px); border-bottom: 1px solid var(--line);
            display: flex; align-items: center; gap: 18px; padding: 0 26px;
            position: sticky; top: 0; z-index: 30;
        }
        .doctor-wordmark { display: flex; align-items: center; gap: 11px; color: var(--ink); }
        .doctor-brand-title { font-size: 17px; line-height: 1.05; font-weight: 900; letter-spacing: -.3px; }
        .doctor-brand-sub { display: block; margin-top: 2px; color: var(--ink-4); font-size: 10.5px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; }
        .doctor-divider { height: 30px; width: 1px; background: var(--line); }
        .doctor-page-title { font-size: 15px; font-weight: 850; letter-spacing: -.2px; }
        .doctor-page-date { font-size: 12.5px; color: var(--ink-3); }
        .doctor-top-actions { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .doctor-userchip {
            display: flex; align-items: center; gap: 10px; padding: 5px 10px 5px 5px;
            border-radius: 12px; border: 1px solid var(--line); background: var(--surface);
        }
        .doctor-user-name { font-size: 13px; font-weight: 800; line-height: 1.12; color: var(--ink); }
        .doctor-user-role { font-size: 11px; color: var(--ink-3); line-height: 1.15; }
        .doctor-scroll { flex: 1; padding: 26px; }
        .doctor-page { max-width: 1240px; margin: 0 auto; }
        .doctor-flash {
            position: fixed; top: 82px; left: 50%; transform: translateX(-50%); z-index: 60;
            background: var(--ink); color: #fff; padding: 12px 18px; border-radius: 12px;
            box-shadow: var(--sh-pop); font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 9px;
        }
        .doctor-flash svg { color: #5be39a; }
        .doctor-stats { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 20px; }
        .doctor-stat {
            background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg); box-shadow: var(--sh);
            padding: 16px 18px; display: flex; align-items: center; gap: 14px; border-left: 0;
        }
        .doctor-stat-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; flex-shrink: 0; }
        .doctor-stat-value { font-family: var(--mono); font-size: 24px; font-weight: 900; letter-spacing: -.8px; line-height: 1; color: var(--ink); }
        .doctor-stat-value small { font-size: 13px; color: var(--ink-4); font-weight: 700; }
        .doctor-stat-label { font-size: 12.5px; color: var(--ink-3); font-weight: 700; margin-top: 4px; }
        .doctor-grid { display: grid; grid-template-columns: minmax(0, 1fr) 350px; gap: 20px; align-items: start; }
        .doctor-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg); box-shadow: var(--sh); overflow: hidden; }
        .doctor-card-head { display: flex; align-items: center; gap: 12px; padding: 17px 22px; border-bottom: 1px solid var(--line-2); }
        .doctor-card-head h2 { font-size: 15.5px; font-weight: 900; margin: 0; letter-spacing: -.2px; }
        .doctor-card-head .muted { font-size: 12.5px; color: var(--ink-3); font-weight: 700; }
        .doctor-card-body { padding: 26px; }
        .doctor-ticket-box {
            background: var(--surface-2); border: 1px solid var(--brand-100); border-radius: var(--r-lg);
            padding: 26px; text-align: center;
        }
        .doctor-ticket-code { font-family: var(--mono); font-size: 60px; font-weight: 900; color: var(--brand); letter-spacing: -2px; line-height: 1; }
        .doctor-patient-row { display: flex; align-items: center; gap: 12px; margin: 22px 0 16px; }
        .doctor-patient-name { font-size: 17px; font-weight: 900; color: var(--ink); }
        .doctor-patient-meta { font-size: 13px; color: var(--ink-3); }
        .doctor-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .doctor-detail-item { background: var(--surface-2); border-radius: 11px; padding: 11px 14px; min-width: 0; }
        .doctor-detail-label { font-size: 11.5px; color: var(--ink-3); font-weight: 700; }
        .doctor-detail-value { font-size: 14px; font-weight: 800; margin-top: 2px; color: var(--ink); word-break: break-word; }
        .doctor-action-row { display: flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
        .doctor-action-row form { margin: 0; display: inline-flex; }
        .doctor-action-row .btn-flex { flex: 1; }
        .doctor-empty-symbol { font-family: var(--mono); font-size: 44px; font-weight: 900; color: var(--ink-4); line-height: 1; }
        .doctor-side-stack { display: flex; flex-direction: column; gap: 20px; }
        .doctor-list { padding: 8px; display: flex; flex-direction: column; gap: 4px; max-height: 320px; overflow-y: auto; }
        .doctor-list.done { max-height: 225px; gap: 2px; }
        .doctor-list-empty { padding: 26px; text-align: center; color: var(--ink-4); font-size: 13px; font-weight: 650; }
        .doctor-queue-item { display: flex; align-items: center; gap: 10px; padding: 10px 11px; border-radius: 11px; }
        .doctor-queue-item.first { background: var(--call-bg); }
        .doctor-queue-no { font-family: var(--mono); font-size: 12px; color: var(--ink-4); width: 18px; font-weight: 800; }
        .doctor-queue-info { flex: 1; min-width: 0; }
        .doctor-queue-code { font-family: var(--mono); font-weight: 900; font-size: 14px; color: var(--ink); }
        .doctor-queue-sub { font-size: 12px; color: var(--ink-3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doctor-queue-actions { margin: 0; }
        .doctor-queue-actions .btn { padding: 8px 13px; font-size: 12.5px; }
        .doctor-done-item { display: flex; align-items: center; gap: 10px; padding: 9px 11px; border-radius: 10px; }
        .doctor-done-code { font-family: var(--mono); font-weight: 900; font-size: 13.5px; color: var(--ink); }
        .doctor-done-name { flex: 1; font-size: 12.5px; color: var(--ink-3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doctor-done-item form { margin: 0; }
        .doctor-done-item .btn { padding: 5px 9px; font-size: 11.5px; }
        .doctor-off-note {
            margin-bottom: 20px; background: var(--call-bg); color: var(--call); border: 1px solid #fde68a;
            border-radius: var(--r-lg); padding: 14px 16px; display: flex; gap: 10px; align-items: center; font-weight: 750;
        }
        .doctor-off-note svg { flex-shrink: 0; }
        .badge { text-transform: none; }
        .btn form { margin: 0; }
        form { margin: 0; }
        @media (max-width: 1100px) {
            .doctor-grid { grid-template-columns: 1fr; }
            .doctor-stats { grid-template-columns: repeat(2, minmax(0,1fr)); }
        }
        @media (max-width: 760px) {
            .doctor-topbar { height: auto; min-height: 70px; padding: 12px 16px; flex-wrap: wrap; }
            .doctor-divider, .doctor-userchip { display: none; }
            .doctor-top-actions { width: 100%; margin-left: 0; justify-content: flex-start; flex-wrap: wrap; }
            .doctor-scroll { padding: 16px; }
            .doctor-stats { grid-template-columns: 1fr; }
            .doctor-detail-grid { grid-template-columns: 1fr; }
            .doctor-ticket-code { font-size: 44px; }
            .doctor-action-row, .doctor-action-row form, .doctor-action-row .btn { width: 100%; }
            .doctor-action-row .btn-flex { flex: unset; }
        }
    </style>
</head>
<body>
<div class="doctor-app">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="doctor-flash">
            <?= $icon('check', 16) ?>
            <span><?= $e($_SESSION['flash']['message'] ?? '') ?></span>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <header class="doctor-topbar">
        <a class="doctor-wordmark" href="<?= $base ?>/dokter/loket">
            <?php $logoSize = 38; include __DIR__ . '/../../layout/_logo.php'; ?>
            <span>
                <span class="doctor-brand-title">SANTRI</span>
                <span class="doctor-brand-sub">Sistem Antrian RS</span>
            </span>
        </a>

        <div class="doctor-divider"></div>

        <div>
            <div class="doctor-page-title">Loket Dokter</div>
            <div class="doctor-page-date"><?= $e($today) ?></div>
        </div>

        <div class="doctor-top-actions">
            <?= $badge(!empty($isOff) ? 'cancel' : 'done') ?>

            <?php if (!empty($isOff)): ?>
                <form action="<?= $base ?>/dokter/loket/buka" method="post">
                    <button class="btn btn-success" type="submit"><?= $icon('check', 15) ?> Buka Praktik</button>
                </form>
            <?php else: ?>
                <form action="<?= $base ?>/dokter/loket/tutup" method="post" onsubmit="return confirm('Tutup praktik hari ini? Pasien tidak bisa daftar lagi.')">
                    <button class="btn btn-danger" type="submit"><?= $icon('x', 15) ?> Tutup Praktik</button>
                </form>
            <?php endif; ?>

            <div class="doctor-userchip">
                <div class="avatar"><?= $e($initial($doctorName)) ?></div>
                <div>
                    <div class="doctor-user-name"><?= $e($doctorName) ?></div>
                    <div class="doctor-user-role"><?= $e($poliName) ?></div>
                </div>
            </div>

            <a class="admin-icon" href="<?= $base ?>/admin/logout" title="Keluar">
                <?= $icon('logout', 18) ?>
            </a>
        </div>
    </header>

    <main class="doctor-scroll">
        <div class="doctor-page">
            <?php if (!empty($isOff)): ?>
                <div class="doctor-off-note">
                    <?= $icon('shield', 18) ?>
                    <span>Praktik hari ini sedang <strong>ditutup</strong>. Pasien baru tidak bisa mengambil antrean untuk dokter ini.</span>
                </div>
            <?php endif; ?>

            <section class="doctor-stats">
                <div class="doctor-stat">
                    <div class="doctor-stat-icon" style="background: var(--wait-bg); color: var(--wait);"><?= $icon('clock', 21) ?></div>
                    <div>
                        <div class="doctor-stat-value"><?= count($waiting ?? []) ?><small> pasien</small></div>
                        <div class="doctor-stat-label">Menunggu</div>
                    </div>
                </div>
                <div class="doctor-stat">
                    <div class="doctor-stat-icon" style="background: var(--progress-bg); color: var(--progress);"><?= $icon('megaphone', 21) ?></div>
                    <div>
                        <div class="doctor-stat-value"><?= !empty($now) ? 1 : 0 ?><small> loket</small></div>
                        <div class="doctor-stat-label">Sedang dilayani</div>
                    </div>
                </div>
                <div class="doctor-stat">
                    <div class="doctor-stat-icon" style="background: var(--done-bg); color: var(--done);"><?= $icon('check', 21) ?></div>
                    <div>
                        <div class="doctor-stat-value"><?= count($doneOnly) ?><small> pasien</small></div>
                        <div class="doctor-stat-label">Selesai hari ini</div>
                    </div>
                </div>
                <div class="doctor-stat">
                    <div class="doctor-stat-icon" style="background: var(--brand-50); color: var(--brand);"><?= $icon('pulse', 21) ?></div>
                    <div>
                        <div class="doctor-stat-value"><?= $avgMinutes ?><small> mnt</small></div>
                        <div class="doctor-stat-label">Rata-rata layanan</div>
                    </div>
                </div>
            </section>

            <section class="doctor-grid">
                <div class="doctor-card">
                    <div class="doctor-card-head">
                        <?= $icon('megaphone', 17) ?>
                        <h2>Sedang Dilayani</h2>
                    </div>
                    <div class="doctor-card-body">
                        <?php if (!empty($now)): ?>
                            <div class="doctor-ticket-box">
                                <div class="doctor-ticket-code"><?= $e($now['ticket_code'] ?? '-') ?></div>
                                <div style="margin-top:12px"><?= $badge($now['status'] ?? 'call') ?></div>
                            </div>

                            <div class="doctor-patient-row">
                                <div class="avatar"><?= $e($initial($now['patient_name'] ?? 'Pasien')) ?></div>
                                <div>
                                    <div class="doctor-patient-name"><?= $e($now['patient_name'] ?? '-') ?></div>
                                    <div class="doctor-patient-meta"><?= $e(($now['registered_via'] ?? 'online') === 'walkin' ? 'Pasien walk-in' : 'Pasien online') ?></div>
                                </div>
                                <span class="tag" style="margin-left:auto"><?= $e($now['insurance_type'] ?? '-') ?></span>
                            </div>

                            <div class="doctor-detail-grid">
                                <div class="doctor-detail-item">
                                    <div class="doctor-detail-label">Keluhan</div>
                                    <div class="doctor-detail-value"><?= $e(!empty($now['complaint']) ? $now['complaint'] : '-') ?></div>
                                </div>
                                <div class="doctor-detail-item">
                                    <div class="doctor-detail-label">Jam Daftar</div>
                                    <div class="doctor-detail-value mono">
                                        <?= $e(!empty($now['created_at']) ? date('H:i', strtotime($now['created_at'])) . ' WIB' : '-') ?>
                                    </div>
                                </div>
                                <div class="doctor-detail-item">
                                    <div class="doctor-detail-label">No. Antrean</div>
                                    <div class="doctor-detail-value mono">#<?= $e($now['number'] ?? '-') ?></div>
                                </div>
                                <div class="doctor-detail-item">
                                    <div class="doctor-detail-label">Pendaftaran</div>
                                    <div class="doctor-detail-value"><?= $e(($now['registered_via'] ?? 'online') === 'walkin' ? 'Walk-in' : 'Online') ?></div>
                                </div>
                            </div>

                            <div class="doctor-action-row">
                                <?php if (($now['status'] ?? '') === 'call'): ?>
                                    <form class="btn-flex" action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/progress" method="post">
                                        <button class="btn btn-primary btn-lg btn-block" type="submit"><?= $icon('play', 17) ?> Mulai Periksa</button>
                                    </form>
                                    <form action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/skip" method="post" onsubmit="return confirm('Tandai pasien tidak hadir?')">
                                        <button class="btn btn-danger btn-lg" type="submit"><?= $icon('x', 17) ?> Tidak Hadir</button>
                                    </form>
                                <?php elseif (($now['status'] ?? '') === 'progress'): ?>
                                    <form class="btn-flex" action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/done" method="post">
                                        <button class="btn btn-success btn-lg btn-block" type="submit"><?= $icon('check', 17) ?> Selesai Periksa</button>
                                    </form>
                                    <form action="<?= $base ?>/dokter/loket/<?= (int)$now['id'] ?>/done-next" method="post">
                                        <button class="btn btn-primary btn-lg" type="submit"><?= $icon('next', 17) ?> Selesai &amp; Panggil Berikutnya</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="doctor-empty-symbol">—</div>
                                <div style="margin-top:8px">Tidak ada pasien dilayani.<br>Panggil pasien berikutnya dari daftar.</div>
                                <?php if (!empty($firstWaiting) && empty($isOff)): ?>
                                    <form action="<?= $base ?>/dokter/loket/<?= (int)$firstWaiting['id'] ?>/call" method="post" style="margin-top:18px; display:inline-flex">
                                        <button class="btn btn-primary btn-lg" type="submit"><?= $icon('megaphone', 17) ?> Panggil <?= $e($firstWaiting['ticket_code'] ?? '') ?></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <aside class="doctor-side-stack">
                    <div class="doctor-card">
                        <div class="doctor-card-head">
                            <?= $icon('clock', 16) ?>
                            <h2>Menunggu</h2>
                            <span class="muted"><?= count($waiting ?? []) ?></span>
                        </div>
                        <div class="doctor-list">
                            <?php if (empty($waiting)): ?>
                                <div class="doctor-list-empty">Antrean kosong.</div>
                            <?php else: ?>
                                <?php foreach ($waiting as $i => $w): ?>
                                    <div class="doctor-queue-item <?= $i === 0 ? 'first' : '' ?>">
                                        <span class="doctor-queue-no"><?= $i + 1 ?></span>
                                        <div class="doctor-queue-info">
                                            <div class="doctor-queue-code"><?= $e($w['ticket_code'] ?? '-') ?></div>
                                            <div class="doctor-queue-sub"><?= $e($w['patient_name'] ?? '-') ?> · <?= $e(!empty($w['complaint']) ? $w['complaint'] : 'Tidak ada keluhan') ?></div>
                                        </div>

                                        <?php if (empty($isBusy) && empty($isOff)): ?>
                                            <form class="doctor-queue-actions" action="<?= $base ?>/dokter/loket/<?= (int)$w['id'] ?>/call" method="post">
                                                <button class="btn btn-primary" type="submit"><?= $icon('megaphone', 14) ?> Panggil</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-primary" type="button" disabled><?= $icon('megaphone', 14) ?> Panggil</button>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="doctor-card">
                        <div class="doctor-card-head">
                            <?= $icon('check', 16) ?>
                            <h2>Selesai Hari Ini</h2>
                            <span class="muted"><?= count($selesai ?? []) ?></span>
                        </div>
                        <div class="doctor-list done">
                            <?php if (empty($selesai)): ?>
                                <div class="doctor-list-empty">Belum ada.</div>
                            <?php else: ?>
                                <?php foreach ($selesai as $s): ?>
                                    <div class="doctor-done-item">
                                        <span class="doctor-done-code"><?= $e($s['ticket_code'] ?? '-') ?></span>
                                        <span class="doctor-done-name"><?= $e($s['patient_name'] ?? '-') ?></span>
                                        <?= $badge($s['status'] ?? '-') ?>
                                        <?php if (($s['status'] ?? '') === 'skip' && empty($isBusy) && empty($isOff)): ?>
                                            <form action="<?= $base ?>/dokter/loket/<?= (int)$s['id'] ?>/recall" method="post">
                                                <button class="btn btn-ghost" type="submit">Ulang</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </main>
</div>
</body>
</html>
