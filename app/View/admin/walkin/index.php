<?php $base = BASE_URL; ?>

<div class="section-header">
    <div>
        <h1>Pendaftaran Walk-in</h1>
        <p class="page-title-note">Input pasien yang datang langsung ke rumah sakit.</p>
    </div>
</div>

<p class="alert alert-warning">Form walk-in belum aktif karena controller store masih kosong.</p>

<div class="form-card">
    <form action="<?= $base ?>/admin/walkin" method="post">
        <div class="form-row">
            <p>
                <label>Nama Pasien</label>
                <input type="text" name="walkin_name">
            </p>
            <p>
                <label>NIK</label>
                <input type="text" name="walkin_nik">
            </p>
        </div>
        <p>
            <label>No HP</label>
            <input type="text" name="walkin_phone">
        </p>
        <div class="form-actions">
            <button type="submit">Daftarkan</button>
        </div>
    </form>
</div>