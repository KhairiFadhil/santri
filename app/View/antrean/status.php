<?php
/** @var ?array $antrean */
?>

<h1>Antrean Saya</h1>

<?php if (!$antrean): ?>
    <p>Belum ada antrean aktif.</p>
    <p><a href="/santri-belajar/public/daftar">Daftar antrean baru</a></p>
<?php else: ?>

<div id="queue-info">
    <h2 style="font-size: 48px; margin: 20px 0;" data-ticket>
        <?= htmlspecialchars($antrean['ticket_code']) ?>
    </h2>

    <p>
        Status: <strong data-status><?= htmlspecialchars(strtoupper($antrean['status'])) ?></strong>
        <small style="color: #888;">(otomatis update tiap 4 detik)</small>
    </p>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr><th>Poli</th><td><?= htmlspecialchars($antrean['poli_name']) ?></td></tr>
        <tr><th>Dokter</th><td><?= htmlspecialchars($antrean['doctor_name']) ?></td></tr>
        <tr><th>Tanggal</th><td><?= htmlspecialchars(format_tanggal_id($antrean['schedule_date'])) ?></td></tr>
        <tr><th>Jam Mulai Praktik</th><td><?= htmlspecialchars(substr($antrean['schedule_time'] ?? '', 0, 5)) ?></td></tr>
        <tr><th>Pasien di Depan Anda</th><td><strong data-ahead>—</strong> orang</td></tr>
        <tr><th>Estimasi Waktu Tunggu</th><td><strong data-eta>—</strong> menit</td></tr>
        <tr><th>Sedang Dipanggil</th><td><strong data-calling>—</strong></td></tr>
    </table>

    <p data-message style="font-weight: bold;"></p>

    <form method="POST" action="/santri-belajar/public/antrean/cancel" style="margin-top: 20px;"
          onsubmit="return confirm('Yakin batalkan antrean ini?');">
        <input type="hidden" name="queue_id" value="<?= $antrean['id'] ?>">
        <button type="submit">Batalkan Antrean</button>
    </form>
</div>

<script>
(function () {
    const $status  = document.querySelector('[data-status]');
    const $ahead   = document.querySelector('[data-ahead]');
    const $eta     = document.querySelector('[data-eta]');
    const $calling = document.querySelector('[data-calling]');
    const $msg     = document.querySelector('[data-message]');
    let lastStatus = '<?= $antrean['status'] ?>';
    let timer = null;

    function stopPolling(text, color) {
        $msg.textContent = text;
        $msg.style.color = color;
        if (timer) clearInterval(timer);
    }

    async function poll() {
        try {
            const res = await fetch('/santri-belajar/public/api/queue/status', { credentials: 'same-origin', cache: 'no-store' });
            const data = await res.json();

            if (!data.ok || !data.queue) {
                stopPolling('Antrean sudah berakhir. Cek halaman Riwayat untuk detail.', '#666');
                return;
            }

            const q = data.queue;
            $status.textContent  = q.status.toUpperCase();
            $ahead.textContent   = q.ahead;
            $eta.textContent     = q.eta_minutes;
            $calling.textContent = q.calling || '—';

            if (q.status === 'done') {
                stopPolling('Antrean Anda sudah selesai dilayani. Terima kasih.', 'green');
                return;
            }
            if (q.status === 'skip') {
                stopPolling('Antrean Anda dilewati karena tidak hadir saat dipanggil. Silakan daftar ulang.', 'orange');
                return;
            }
            if (q.status === 'cancel') {
                stopPolling('Antrean dibatalkan.', '#888');
                return;
            }
            if (q.status === 'call') {
                $msg.textContent = 'Giliran Anda! Silakan menuju ' + q.poli_name + '.';
                $msg.style.color = 'green';
            } else if (q.status === 'progress') {
                $msg.textContent = 'Anda sedang diperiksa dokter.';
                $msg.style.color = 'blue';
            } else if (q.ahead <= 2 && q.ahead > 0) {
                $msg.textContent = 'Bersiap, sebentar lagi giliran Anda.';
                $msg.style.color = 'orange';
            } else {
                $msg.textContent = '';
            }
            lastStatus = q.status;
        } catch (err) {
        }
    }

    poll();
    timer = setInterval(poll, 4000);
})();
</script>

<?php endif; ?>

<p style="margin-top: 20px;"><a href="/santri-belajar/public/dashboard">← Dashboard</a></p>
