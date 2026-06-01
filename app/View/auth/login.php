<?php /** @var string $error */ /** @var string $email */ ?>

<div class="auth">
    <!-- panel brand -->
    <div class="auth-brand">
        <a class="auth-logo" href="<?= BASE_URL ?>/">
            <?php $logoSize = 42; $logoPlain = true; $logoColor = '#fff'; include __DIR__ . '/../layout/_logo.php'; ?>
            <span>SANTRI</span>
        </a>
        <div class="auth-brand-mid">
            <h2>Selamat datang kembali.</h2>
            <p>Masuk untuk mengelola antrean Anda, memantau posisi real-time, dan melihat riwayat kunjungan.</p>
            <div class="auth-ticket">
                <div class="auth-ticket-label" id="ticket_label">Antrean hari ini</div>
                <div id="poli_code" class="mono auth-ticket-no">—</div>
                <div id="poli_waiting" class="auth-ticket-sub">Memuat antrean&hellip;</div>
            </div>
        </div>
        <div class="auth-brand-foot">&copy; <?= date('Y') ?> <?= HOSPITAL_NAME ?> &middot; SANTRI</div>
        <span class="auth-blob auth-blob-1"></span>
        <span class="auth-blob auth-blob-2"></span>
    </div>

    <!-- panel form -->
    <div class="auth-form">
        <div class="auth-form-top">
            <a class="btn btn-ghost" href="<?= BASE_URL ?>/">&larr; Beranda</a>
        </div>
        <div class="auth-form-mid">
            <div class="auth-box">
                <h1>Masuk ke SANTRI</h1>
                <p class="auth-sub">Masukkan email dan kata sandi akun pasien Anda.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (!empty($_SESSION['flash'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']['message']) ?></div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/login">
                    <label class="auth-label">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="nama@email.com" required>

                    <label class="auth-label">Kata Sandi</label>
                    <input type="password" name="password" placeholder="••••••••" required>

                    <button class="btn btn-primary btn-lg btn-block" type="submit" style="margin-top:8px">Masuk</button>
                </form>

                <div class="auth-alt">
                    Belum punya akun? <a href="<?= BASE_URL ?>/register">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const endpoint = '<?= BASE_URL ?>/api/queue/live';
    const label   = document.getElementById('ticket_label');
    const codeEl  = document.getElementById('poli_code');
    const subEl   = document.getElementById('poli_waiting');
    let data = [];
    let idx = 0;

    function render() {
        const calling = data.filter(r => r.now_serving);
        const pool = calling.length
            ? calling
            : data.filter(r => r.waiting > 0 || (r.next && r.next.length));

        if (!pool.length) {
            label.textContent = 'Antrean hari ini';
            codeEl.textContent = '—';
            subEl.textContent = 'Belum ada antrean aktif';
            return;
        }

        if (idx >= pool.length) idx = 0;
        const row = pool[idx];
        idx++;

        const isCalling = !!row.now_serving;
        const code = isCalling
            ? row.now_serving.ticket_code
            : (row.next && row.next.length ? row.next[0].ticket_code : '—');

        label.textContent = isCalling ? 'Sedang dipanggil' : 'Antrean berikutnya';
        codeEl.textContent = code;
        subEl.textContent = row.poli_name + ' · ' + row.waiting + ' antre menunggu';
    }

    async function refresh() {
        try {
            const res = await fetch(endpoint, { cache: 'no-store' });
            const json = await res.json();
            if (!json.ok) return;
            data = json.live;
            render();
        } catch (e) {}
    }

    refresh();
    setInterval(refresh, 5000);
    setInterval(render, 3000);
})();
</script>
