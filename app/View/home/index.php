<?php /** @var array $poli */ /** @var array $live */ ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-dots"></div>
  <div class="hero-glow"></div>
  <div class="hero-grid">
    <div class="hero-text">
        <h1 class="hero-title">Antre dari rumah,<br>datang <span class="hl-key">tepat&nbsp;waktu</span>.</h1>
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
        <div class="card hero-ticket" id="hero-card">
            <div class="hero-ticket-top">
                <span class="hero-ticket-label" id="hero-label">Antrean Hari Ini</span>
                <span class="badge call" id="hero-badge"><i></i>Live</span>
            </div>
            <div class="mono hero-ticket-no" id="hero-no">—</div>
            <div class="hero-ticket-sub" id="hero-sub">Menunggu antrean dipanggil</div>
            <div class="hero-ticket-meta">
                <div><div class="mono hero-ticket-mv" id="hero-wait">0</div><div class="hero-ticket-ml">pasien menunggu</div></div>
                <div><div class="mono hero-ticket-mv" id="hero-poli">—</div><div class="hero-ticket-ml">poli</div></div>
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
            <div class="live-empty">Belum ada antrean aktif hari ini.</div>
        <?php else: ?>
            <?php foreach ($live as $row): ?>
                <div class="card card-pad live-card">
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
<section id="poli" class="home-sec">
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
    let data = [];
    let idx = 0;
   
    function liveCard(row) {
        const no = row.now_serving ? row.now_serving.ticket_code : '—';
        return `
            <div class="card card-pad live-card">
                <div class="live-card-head">
                    <span class="tag mono">${row.poli_code}</span>
                    <span class="live-card-poli">${row.poli_name}</span>
                </div>
                <div class="live-card-label">Sedang dipanggil</div>
                <div class="mono live-card-no${row.now_serving ? '' : ' off'}">${no}</div>
                <div class="live-card-wait"><span class="waiting">${row.waiting}</span> pasien menunggu</div>
            </div>`;
    }

    function renderHero() {
        const label = document.getElementById('hero-label');
        const no = document.getElementById('hero-no');
        const sub = document.getElementById('hero-sub');
        const wait = document.getElementById('hero-wait');
        const poli = document.getElementById('hero-poli');
        const badge = document.getElementById('hero-badge');

        // utamakan yang sedang dipanggil; kalau tidak ada, tampilkan antrean berikutnya yang menunggu
        const calling = data.filter(r => r.now_serving);
        const pool = calling.length
            ? calling
            : data.filter(r => r.waiting > 0 || (r.next && r.next.length));

        if (!pool.length) {
            label.textContent = 'Antrean Hari Ini';
            no.textContent = '—';
            no.classList.add('off');
            sub.textContent = 'Belum ada antrean aktif';
            wait.textContent = '0';
            poli.textContent = '—';
            badge.style.display = 'none';
            return;
        }

        if (idx >= pool.length) idx = 0;
        const row = pool[idx];
        idx++;

        const isCalling = !!row.now_serving;
        const code = isCalling
            ? row.now_serving.ticket_code
            : (row.next && row.next.length ? row.next[0].ticket_code : '—');

        label.textContent = isCalling ? 'Sedang Dipanggil' : 'Antrean Berikutnya';
        badge.style.display = '';
        badge.className = 'badge ' + (isCalling ? 'call' : 'wait');
        badge.innerHTML = '<i></i>' + (isCalling ? 'Dipanggil' : 'Menunggu');
        no.textContent = code;
        no.classList.toggle('off', code === '—');
        sub.textContent = row.poli_name + ' · ' + row.doctor_name;
        wait.textContent = row.waiting;
        poli.textContent = row.poli_code;
    }

    async function refresh() {
        try {
            const res = await fetch(endpoint, { cache: 'no-store' });
            const json = await res.json();
            if (!json.ok) return;
            data = json.live;
            board.innerHTML = data.length
                ? data.map(liveCard).join('')
                : '<div class="live-empty">Belum ada antrean aktif hari ini.</div>';
            updatedAt.textContent = new Date().toLocaleTimeString('id-ID');
            renderHero();
        } catch (e) {}
    }

    refresh();
    setInterval(refresh, 5000);
    setInterval(renderHero, 3000);
})();
</script>
