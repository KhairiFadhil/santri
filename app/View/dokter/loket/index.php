<?php
/** @var array<string, mixed> $dokter */
/** @var array<int, array<string, mixed>> $waiting */
/** @var array<string, mixed>|null $now */
/** @var array<int, array<string, mixed>> $selesai */
/** @var bool $isOff */
/** @var bool $isBusy */

require_once __DIR__ . '/partials/_helpers.php';

$base = defined('BASE_URL') ? BASE_URL : '/santri-belajar/public';
$today = function_exists('format_tanggal_id') ? format_tanggal_id(date('Y-m-d')) : date('l, d F Y');

$doneOnly = array_values(array_filter($selesai ?? [], static fn($row) => ($row['status'] ?? '') === 'done'));
$avgMinutes = defined('ANTRIAN_PERORANG') ? ANTRIAN_PERORANG * 3 : 9;
$firstWaiting = !empty($waiting) ? $waiting[0] : null;
$doctorName = $dokter['name'] ?? 'Dokter';
$poliName = $dokter['poli_name'] ?? 'Poli';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loket Dokter — SANTRI</title>

    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/dokter-loket.css">
</head>

<body>
    <div class="doctor-app">
        <?php include __DIR__ . '/partials/_flash.php'; ?>
        <?php include __DIR__ . '/partials/_topbar.php'; ?>

        <main class="doctor-scroll">
            <div class="doctor-page">
                <?php include __DIR__ . '/partials/_off_note.php'; ?>
                <?php include __DIR__ . '/partials/_stats.php'; ?>

                <section class="doctor-grid">
                    <?php include __DIR__ . '/partials/_current_patient.php'; ?>

                    <aside class="doctor-side-stack">
                        <?php include __DIR__ . '/partials/_waiting_list.php'; ?>
                        <?php include __DIR__ . '/partials/_done_list.php'; ?>
                    </aside>
                </section>
            </div>
        </main>
    </div>
</body>

</html>
