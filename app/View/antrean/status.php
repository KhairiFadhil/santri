<?php /** @var ?array $antrean */ ?>

<div class="user-page">
    <div class="section-header user-section-head">
        <div>
            <h1>Antrean Saya</h1>
            <p class="page-title-note">Status antrean diperbarui otomatis setiap 4 detik.</p>
        </div>
        <a class="btn btn-ghost" href="<?= BASE_URL ?>/dashboard">← Dashboard</a>
    </div>

    <?php if (!$antrean): ?>
        <div class="empty-state">
            <h3>Belum ada antrean aktif</h3>
            <p>Silakan daftar antrean baru untuk mendapatkan nomor kunjungan.</p>
            <div class="mt-2"><a class="btn btn-primary" href="<?= BASE_URL ?>/daftar">Daftar Antrean Baru</a></div>
        </div>
    <?php else: ?>
        <section id="queue-info" class="queue-status-card card">
            <div class="queue-status-top">
                <div>
                    <span class="eyebrow">Nomor Antrean</span>
                    <div class="queue-ticket queue-ticket-lg mono" data-ticket><?= htmlspecialchars($antrean['ticket_code']) ?></div>
                    <span class="badge <?= htmlspecialchars($antrean['status']) ?>" data-status-wrap><i></i><span data-status><?= htmlspecialchars(strtoupper($antrean['status'])) ?></span></span>
                </div>
                <div class="queue-message" data-message></div>
            </div>

            <div class="queue-detail-grid queue-detail-grid-wide mt-3">
                <div><span>Poli</span><strong><?= htmlspecialchars($antrean['poli_name']) ?></strong></div>
                <div><span>Dokter</span><strong><?= htmlspecialchars($antrean['doctor_name']) ?></strong></div>
                <div><span>Tanggal</span><strong><?= htmlspecialchars(format_tanggal_id($antrean['schedule_date'])) ?></strong></div>
                <div><span>Jam Mulai</span><strong><?= htmlspecialchars(substr($antrean['schedule_time'] ?? '', 0, 5)) ?></strong></div>
                <div><span>Pasien di Depan</span><strong><span data-ahead>—</span> orang</strong></div>
                <div><span>Estimasi Tunggu</span><strong><span data-eta>—</span> menit</strong></div>
                <div><span>Sedang Dipanggil</span><strong data-calling>—</strong></div>
            </div>

            <form method="POST" action="<?= BASE_URL ?>/antrean/cancel" class="mt-3"
                  onsubmit="return confirm('Yakin batalkan antrean ini?');">
                <input type="hidden" name="queue_id" value="<?= $antrean['id'] ?>">
                <button class="btn btn-danger" type="submit">Batalkan Antrean</button>
            </form>
        </section>

        <script>
        (function () {
            const $status  = document.querySelector('[data-status]');
            const $wrap    = document.querySelector('[data-status-wrap]');
            const $ahead   = document.querySelector('[data-ahead]');
            const $eta     = document.querySelector('[data-eta]');
            const $calling = document.querySelector('[data-calling]');
            const $msg     = document.querySelector('[data-message]');
            let timer = null;

            function stopPolling(text, cls) {
                $msg.textContent = text;
                $msg.className = 'queue-message ' + (cls || '');
                if (timer) clearInterval(timer);
            }

            async function poll() {
                try {
                    const res = await fetch('<?= BASE_URL ?>/api/queue/status', { credentials: 'same-origin', cache: 'no-store' });
                    const data = await res.json();

                    if (!data.ok || !data.queue) {
                        stopPolling('Antrean sudah berakhir. Cek halaman Riwayat untuk detail.', 'muted-msg');
                        return;
                    }

                    const q = data.queue;
                    $status.textContent  = q.status.toUpperCase();
                    $wrap.className = 'badge ' + q.status;
                    $ahead.textContent   = q.ahead;
                    $eta.textContent     = q.eta_minutes;
                    $calling.textContent = q.calling || '—';

                    if (q.status === 'done') return stopPolling('Antrean Anda sudah selesai dilayani. Terima kasih.', 'ok-msg');
                    if (q.status === 'skip') return stopPolling('Antrean Anda dilewati karena tidak hadir saat dipanggil. Silakan daftar ulang.', 'warn-msg');
                    if (q.status === 'cancel') return stopPolling('Antrean dibatalkan.', 'muted-msg');

                    if (q.status === 'call') {
                        $msg.textContent = 'Giliran Anda! Silakan menuju ' + q.poli_name + '.';
                        $msg.className = 'queue-message ok-msg';
                    } else if (q.status === 'progress') {
                        $msg.textContent = 'Anda sedang diperiksa dokter.';
                        $msg.className = 'queue-message info-msg';
                    } else if (q.ahead <= 2 && q.ahead > 0) {
                        $msg.textContent = 'Bersiap, sebentar lagi giliran Anda.';
                        $msg.className = 'queue-message warn-msg';
                    } else {
                        $msg.textContent = '';
                        $msg.className = 'queue-message';
                    }
                } catch (err) {}
            }

            poll();
            timer = setInterval(poll, 4000);
        })();
        </script>
    <?php endif; ?>
</div>