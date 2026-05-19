<?php /** @var array $doctors */ /** @var array $errors */ /** @var array $form */ ?>

<h1>Daftar Antrean</h1>

<?php if (!empty($errors)): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="/santri-belajar/public/daftar">
    <p>
        <label>Pilih Dokter:<br>
            <select name="doctor_id" required>
                <option value="">-- pilih dokter --</option>
                <?php foreach ($doctors as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= $form['doctor_id']==$d['id']?'selected':'' ?>>
                        <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['poli_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Tanggal:<br>
            <input type="date" name="schedule_date" value="<?= htmlspecialchars($form['schedule_date']) ?>" required>
        </label>
    </p>

    <p>
        <label>Jam:<br>
            <input type="time" name="schedule_time" value="<?= htmlspecialchars($form['schedule_time']) ?>" required>
        </label>
    </p>

    <p>
        <label>Keluhan (opsional):<br>
            <textarea name="complaint" rows="3" cols="40"><?= htmlspecialchars($form['complaint']) ?></textarea>
        </label>
    </p>

    <p>
        <button type="submit">Ambil Nomor Antrean</button>
    </p>
</form>

<p><a href="/santri-belajar/public/dashboard">← Kembali ke dashboard</a></p>
