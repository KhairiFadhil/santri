<?php /** @var array $rows */ ?>

<h1>Riwayat Antrean</h1>

<?php if (empty($rows)): ?>
    <p>Belum ada riwayat antrean.</p>
<?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nomor</th>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Status</th>
                <th>Keluhan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars(format_tanggal_id($r['schedule_date'])) ?></td>
                    <td><?= htmlspecialchars($r['ticket_code']) ?></td>
                    <td><?= htmlspecialchars($r['poli_name']) ?></td>
                    <td><?= htmlspecialchars($r['doctor_name']) ?></td>
                    <td><?= htmlspecialchars(strtoupper($r['status'])) ?></td>
                    <td><?= htmlspecialchars($r['complaint'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p style="margin-top: 20px;"><a href="/santri-belajar/public/dashboard">← Dashboard</a></p>
