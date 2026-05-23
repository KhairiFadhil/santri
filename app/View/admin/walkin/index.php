<?php $base = '/santri-belajar/public'; ?>

<h1>Pendaftaran Walk-in</h1>

<p>Form walk-in belum aktif karena controller store masih kosong.</p>

<form action="<?= $base ?>/admin/walkin" method="post">
    <p>
        <label>Nama Pasien</label><br>
        <input type="text" name="walkin_name">
    </p>
    <p>
        <label>NIK</label><br>
        <input type="text" name="walkin_nik">
    </p>
    <p>
        <label>No HP</label><br>
        <input type="text" name="walkin_phone">
    </p>
    <button type="submit">Daftarkan</button>
</form>
