<?php
/** @var array $doctors */
/** @var array $errors */
/** @var array $form */
/** @var string $hari */
?>

<h1>Daftar Antrean</h1>

<form method="GET" action="/santri-belajar/public/daftar">
    <p>
        <label>Pilih Tanggal Kunjungan (max 3 hari ke depan):
            <input type="date" name="schedule_date"
                   value="<?= htmlspecialchars($form['schedule_date']) ?>"
                   min="<?= date('Y-m-d') ?>"
                   max="<?= date('Y-m-d', strtotime('+3 days')) ?>"
                   onchange="this.form.submit()">
        </label>
    </p>
</form>

<p>Tanggal: <strong><?= htmlspecialchars(format_tanggal_id($form['schedule_date'])) ?></strong></p>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (empty($doctors)): ?>
    <p>Tidak ada dokter yang praktik di hari <?= htmlspecialchars($hari) ?>. Silakan pilih tanggal lain.</p>
<?php else: ?>
    <h2>Dokter yang Praktik</h2>
    <p><em>Pilih salah satu dokter di bawah:</em></p>

    <form method="POST" action="/santri-belajar/public/daftar">
        <input type="hidden" name="schedule_date" value="<?= htmlspecialchars($form['schedule_date']) ?>">

        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Dokter</th>
                    <th>Poli</th>
                    <th>Jam Praktik</th>
                    <th>Sisa Kuota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctors as $d): ?>
                    <tr>
                        <td>
                            <?php if (!empty($d['penuh'])): ?>
                                <input type="radio" disabled>
                            <?php else: ?>
                                <input type="radio" name="doctor_id" value="<?= $d['id'] ?>"
                                       <?= $form['doctor_id']==$d['id']?'checked':'' ?> required>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($d['name']) ?></strong><br>
                            <small><?= htmlspecialchars($d['specialization']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($d['poli_name']) ?></td>
                        <td><?= substr($d['time_start'], 0, 5) ?> – <?= substr($d['time_end'], 0, 5) ?></td>
                        <td>
                            <?php if (!empty($d['penuh'])): ?>
                                <strong>PENUH</strong>
                            <?php else: ?>
                                <?= isset($d['sisa']) ? (int)$d['sisa'] : '-' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            <label>Keluhan (opsional):<br>
                <textarea name="complaint" rows="3" cols="40"><?= htmlspecialchars($form['complaint']) ?></textarea>
            </label>
        </p>

        <p>
            <button type="submit">Ambil Nomor Antrean</button>
        </p>
    </form>
<?php endif; ?>

<p><a href="/santri-belajar/public/dashboard">← Kembali ke dashboard</a></p>
