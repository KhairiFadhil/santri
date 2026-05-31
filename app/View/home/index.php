<?php /** @var array $poli */ /** @var array $live */ ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-grid">
    <div class="hero-text">
        <h1 class="hero-title">Antre dari rumah,<br>datang tepat waktu.</h1>
        <p class="hero-desc">Pilih dokter, ambil nomor antrean online, dan pantau posisi antrean Anda secara real-time. Tanpa antre panjang di rumah sakit.</p>
        <div class="hero-actions">
            <?php if (isset($_SESSION['user'])): ?>
                <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>/daftar">Daftar Antrean Sekarang</a>
                <a class="btn btn-ghost btn-lg" href="<?= BASE_URL ?>/antrean">Antrean Saya</a>
            <?php else: ?>
                <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>/register">Daftar Antrean Sekarang</a>
                <a class="btn btn-ghost btn-lg" href="#live">Lihat Antrean Live</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero-visual">
        <div class="card hero-ticket">
            <div class="hero-ticket-top">
                <span class="hero-ticket-label">Nomor Antrean Anda</span>
                <span class="badge call"><i></i>Dipanggil</span>
            </div>
            <div class="mono hero-ticket-no">UMU-027</div>
            <div class="hero-ticket-sub">Poli Umum &middot; dr. Ayu Larasati</div>
            <div class="hero-ticket-meta">
                <div><div class="mono hero-ticket-mv">0</div><div class="hero-ticket-ml">antre di depan</div></div>
                <div><div class="mono hero-ticket-mv">08:30</div><div class="hero-ticket-ml">jam praktik</div></div>
            </div>
        </div>
    </div>
  </div>
</section>

<!-- LIVE BOARD -->
<section id="live" class="home-sec">
    <div class="live-head">
        <span class="live-dot"></span>
        <h2>Antrean Live Hari Ini</h2>
        <span class="live-note">Update otomatis &middot; <span id="live-updated">-</span></span>
    </div>

    <div id="live-board">
        <?php if (empty($live)): ?>
            <p><em>Belum ada antrean aktif hari ini.</em></p>
        <?php else: ?>
            <?php foreach ($live as $row): ?>
                <div class="card card-pad live-card" data-doctor-id="<?= (int)$row['doctor_id'] ?>">
                    <div class="live-card-head">
                        <span class="tag mono"><?= htmlspecialchars($row['poli_code']) ?></span>
                        <span class="live-card-poli"><?= htmlspecialchars($row['poli_name']) ?></span>
                    </div>
                    <div class="live-card-label">Sedang dipanggil</div>
                    <div class="mono live-card-no<?= $row['now_serving'] ? '' : ' off' ?>">
                        <?= $row['now_serving'] ? htmlspecialchars($row['now_serving']['ticket_code']) : '—' ?>
                    </div>
                    <div class="live-card-wait"><span class="waiting"><?= (int)$row['waiting'] ?></span> pasien menunggu</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- POLI GRID -->
<section class="home-sec home-sec-alt">
    <h2 class="home-sec-title">Poliklinik Tersedia</h2>
    <p class="home-sec-desc">Layanan poliklinik dengan dokter berpengalaman.</p>
    <?php if (empty($poli)): ?>
        <p><em>Belum ada poli aktif.</em></p>
    <?php else: ?>
        <div class="poli-grid">
            <?php foreach ($poli as $p): ?>
                <div class="card card-pad poli-item">
                    <span class="poli-code mono"><?= htmlspecialchars($p['code']) ?></span>
                    <div>
                        <div class="poli-name"><?= htmlspecialchars($p['name']) ?></div>
                        <div class="poli-sub"><?= htmlspecialchars($p['sub']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- CTA -->
<section class="cta">
    <div class="cta-text">
        <h2>Siap mengantre lebih nyaman?</h2>
        <?php if (isset($_SESSION['user'])): ?>
            <p>Pilih dokter, tentukan tanggal, dan ambil nomor antrean Anda hari ini.</p>
        <?php else: ?>
            <p>Buat akun pasien dan ambil nomor antrean pertama Anda hari ini. Gratis dan mudah.</p>
        <?php endif; ?>
    </div>
    <div class="cta-actions">
        <?php if (isset($_SESSION['user'])): ?>
            <a class="btn btn-lg cta-btn" href="<?= BASE_URL ?>/daftar">Ambil Nomor Antrean</a>
        <?php else: ?>
            <a class="btn btn-lg cta-btn" href="<?= BASE_URL ?>/register">Daftar Akun</a>
            <a class="btn btn-lg cta-btn-ghost" href="<?= BASE_URL ?>/login">Masuk</a>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    const board = document.getElementById('live-board');
    const updatedAt = document.getElementById('live-updated');
    const endpoint = '<?= BASE_URL ?>/api/queue/live';

    function card(row) {
        const no = row.now_serving ? row.now_serving.ticket_code : '—';
        return `
            <div class="card card-pad live-card" data-doctor-id="${row.doctor_id}">
                <div class="live-card-head">
                    <span class="tag mono">${row.poli_code}</span>
                    <span class="live-card-poli">${row.poli_name}</span>
                </div>
                <div class="live-card-label">Sedang dipanggil</div>
                <div class="mono live-card-no${row.now_serving ? '' : ' off'}">${no}</div>
                <div class="live-card-wait"><span class="waiting">${row.waiting}</span> pasien menunggu</div>
            </div>`;
    }

    async function refresh() {
        try {
            const res = await fetch(endpoint, { cache: 'no-store' });
            const data = await res.json();
            if (!data.ok) return;
            board.innerHTML = data.live.length
                ? data.live.map(card).join('')
                : '<p><em>Belum ada antrean aktif hari ini.</em></p>';
            updatedAt.textContent = new Date().toLocaleTimeString('id-ID');
        } catch (e) {}
    }
    refresh();
    setInterval(refresh, 5000);
})();
</script>
