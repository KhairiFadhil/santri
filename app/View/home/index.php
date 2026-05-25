<?php /** @var array $poli */ /** @var array $live */ ?>

<h1>Selamat Datang di SANTRI</h1>
<p>Sistem Antrian Rumah Sakit Terintegrasi — <strong><?= HOSPITAL_NAME ?></strong>.</p>
<p>Daftar antrean online tanpa perlu antre lama di rumah sakit. Pilih dokter, tentukan tanggal, dapat nomor antrean langsung.</p>

<h2>Antrean Live Hari Ini</h2>
<p><small>Update otomatis tiap 5 detik. Terakhir update: <span id="live-updated">-</span></small></p>

<div id="live-board">
    <?php if (empty($live)): ?>
        <p><em>Belum ada antrean aktif hari ini.</em></p>
    <?php else: ?>
        <?php foreach ($live as $row): ?>
            <div class="live-card" data-doctor-id="<?= (int)$row['doctor_id'] ?>">
                <h3>
                    <?= htmlspecialchars($row['poli_name']) ?>
                    <small>(<?= htmlspecialchars($row['doctor_name']) ?>)</small>
                </h3>
                <p>
                    Sedang dipanggil:
                    <?php if ($row['now_serving']): ?>
                        <strong class="now"><?= htmlspecialchars($row['now_serving']['ticket_code']) ?></strong>
                        (<?= $row['now_serving']['status'] === 'progress' ? 'sedang diperiksa' : 'memanggil' ?>)
                    <?php else: ?>
                        <em class="now">-</em>
                    <?php endif; ?>
                </p>
                <p>
                    Berikutnya:
                    <span class="next">
                        <?php if (empty($row['next'])): ?>
                            <em>tidak ada</em>
                        <?php else: ?>
                            <?php foreach ($row['next'] as $i => $n): ?>
                                <?= $i ? ', ' : '' ?><?= htmlspecialchars($n['ticket_code']) ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </span>
                </p>
                <p><small>Total menunggu: <span class="waiting"><?= (int)$row['waiting'] ?></span> orang</small></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<h2>Poli yang Tersedia</h2>
<?php if (empty($poli)): ?>
    <p>Belum ada poli aktif.</p>
<?php else: ?>
    <ul>
        <?php foreach ($poli as $p): ?>
            <li><strong><?= htmlspecialchars($p['name']) ?></strong> — <?= htmlspecialchars($p['sub']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Mulai</h2>
<?php if (isset($_SESSION['user'])): ?>
    <p>
        <a href="/santri-belajar/public/dashboard">Buka Dashboard</a> &middot;
        <a href="/santri-belajar/public/daftar">Daftar Antrean</a>
    </p>
<?php else: ?>
    <p>
        <a href="/santri-belajar/public/login">Masuk</a> &middot;
        <a href="/santri-belajar/public/register">Daftar Akun Baru</a>
    </p>
<?php endif; ?>

<hr>
<p><small>Alamat: <?= HOSPITAL_ADDRESS ?> &middot; Telp: <?= HOSPITAL_PHONE ?></small></p>

<script>
(function () {
    const board = document.getElementById('live-board');
    const updatedAt = document.getElementById('live-updated');
    const endpoint = '/santri-belajar/public/api/queue/live';

    function renderCard(row) {
        const next = row.next.length
            ? row.next.map(n => n.ticket_code).join(', ')
            : '<em>tidak ada</em>';
        const now = row.now_serving
            ? `<strong class="now">${row.now_serving.ticket_code}</strong> (${row.now_serving.status === 'progress' ? 'sedang diperiksa' : 'memanggil'})`
            : '<em class="now">-</em>';
        return `
            <div class="live-card" data-doctor-id="${row.doctor_id}">
                <h3>${row.poli_name} <small>(${row.doctor_name})</small></h3>
                <p>Sedang dipanggil: ${now}</p>
                <p>Berikutnya: <span class="next">${next}</span></p>
                <p><small>Total menunggu: <span class="waiting">${row.waiting}</span> orang</small></p>
            </div>
        `;
    }

    async function refresh() {
        try {
            const res = await fetch(endpoint, { cache: 'no-store' });
            const data = await res.json();
            if (!data.ok) return;
            if (!data.live.length) {
                board.innerHTML = '<p><em>Belum ada antrean aktif hari ini.</em></p>';
            } else {
                board.innerHTML = data.live.map(renderCard).join('');
            }
            updatedAt.textContent = new Date().toLocaleTimeString('id-ID');
        } catch (e) {
            console.error('live queue refresh failed', e);
        }
    }

    refresh();
    setInterval(refresh, 5000);
})();
</script>
