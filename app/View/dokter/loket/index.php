<?php $base = '/santri-belajar/public'; ?>

<h1>Loket — <?= htmlspecialchars($dokter['name']) ?></h1>
<p><?= date('l, d F Y') ?></p>

<?php if (!empty($_SESSION['flash'])): ?>
    <p><strong><?= htmlspecialchars($_SESSION['flash']['message']) ?></strong></p>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>


<h2>Sedang Dilayani</h2>
<?php if (!empty($now)): ?>
    <p>
        <strong><?= htmlspecialchars($now['ticket_code']) ?></strong>
        — <?= htmlspecialchars($now['patient_name'] ?? '-') ?>
        (status: <?= $now['status'] ?>)
    </p>

    <?php if ($now['status'] === 'call'): ?>
        <form action="<?= $base ?>/dokter/loket/<?= $now['id'] ?>/progress" method="post" style="display:inline">
            <button>Mulai Periksa</button>
        </form>
        <form action="<?= $base ?>/dokter/loket/<?= $now['id'] ?>/skip" method="post" style="display:inline">
            <button>Pasien Tidak Hadir</button>
        </form>

    <?php elseif ($now['status'] === 'progress'): ?>
        <form action="<?= $base ?>/dokter/loket/<?= $now['id'] ?>/done" method="post" style="display:inline">
            <button>Selesai</button>
        </form>
        <form action="<?= $base ?>/dokter/loket/<?= $now['id'] ?>/done-next" method="post" style="display:inline">
            <button>Selesai &amp; Panggil Berikutnya</button>
        </form>
    <?php endif; ?>
<?php else: ?>
    <p><em>tidak ada pasien sedang dilayani.</em></p>
<?php endif; ?>


<h2>Menunggu</h2>
<?php if (empty($waiting)): ?>
    <p><em>tidak ada antrean menunggu.</em></p>
<?php else: ?>
    <ol>
        <?php foreach ($waiting as $w): ?>
            <li>
                <strong><?= htmlspecialchars($w['ticket_code']) ?></strong>
                — <?= htmlspecialchars($w['patient_name'] ?? '-') ?>

                <?php if (!$isBusy): ?>
                    <form action="<?= $base ?>/dokter/loket/<?= $w['id'] ?>/call" method="post" style="display:inline">
                        <button>Panggil</button>
                    </form>
                <?php else: ?>
                    <small>(selesaikan pasien sekarang dulu)</small>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>


<h2>Selesai Hari Ini</h2>
<?php if (empty($selesai)): ?>
    <p><em>belum ada.</em></p>
<?php else: ?>
    <ul>
        <?php foreach ($selesai as $s): ?>
            <li>
                <?= htmlspecialchars($s['ticket_code']) ?>
                — <?= htmlspecialchars($s['patient_name'] ?? '-') ?>
                (<?= $s['status'] ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<hr>
<p><a href="<?= $base ?>/admin/logout">Logout</a></p>
