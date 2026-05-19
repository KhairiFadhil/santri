<?php /** @var ?array $antrean */ ?>

<h1>Antrean Saya</h1>

<?php if (!$antrean): ?>
    <p>Belum ada antrean aktif.</p>
    <p><a href="/santri-belajar/public/daftar">Daftar antrean baru</a></p>
<?php else: ?>
    <h2 style="font-size: 48px; margin: 20px 0;">
        <?= htmlspecialchars($antrean['ticket_code']) ?>
    </h2>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>Poli</th><td><?= htmlspecialchars($antrean['poli_name']) ?></td></tr>
        <tr><th>Dokter</th><td><?= htmlspecialchars($antrean['doctor_name']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= htmlspecialchars($antrean['schedule_date']) ?></td></tr>
        <tr><th>Jam</th><td><?= htmlspecialchars($antrean['schedule_time']) ?></td></tr>
        <tr><th>Status</th><td><strong><?= htmlspecialchars(strtoupper($antrean['status'])) ?></strong></td></tr>
        <tr><th>Keluhan</th><td><?= htmlspecialchars($antrean['complaint'] ?? '-') ?></td></tr>
    </table>

    <form method="POST" action="/santri-belajar/public/antrean/cancel" style="margin-top: 20px;"
          onsubmit="return confirm('Yakin batalkan antrean ini?');">
        <input type="hidden" name="queue_id" value="<?= $antrean['id'] ?>">
        <button type="submit">Batalkan Antrean</button>
    </form>
<?php endif; ?>

<p style="margin-top: 20px;"><a href="/santri-belajar/public/dashboard">← Dashboard</a></p>
