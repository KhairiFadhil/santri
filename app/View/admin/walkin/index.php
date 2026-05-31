<?php $base = BASE_URL; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">

<div class="flex justify-between items-center mb-3">
    <div>
        <h1 style="margin: 0; font-size: 24px;">Pendaftaran Walk-in</h1>
        <p style="margin: 4px 0 0; color: var(--teks-3);">Input pasien yang datang langsung ke rumah sakit.</p>
    </div>
</div>

<div class="alert alert-warning mb-3" style="font-weight: 500;">
    ⚠️ Form walk-in belum aktif karena controller store masih kosong.
</div>

<div class="card">
    <form action="<?= $base ?>/admin/walkin" method="post">
        
        <div class="flex gap-2 mb-2">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Nama Pasien</label>
                <input type="text" name="walkin_name" class="form-control" placeholder="Contoh: Siti Aminah" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">No HP</label>
                <input type="text" name="walkin_phone" class="form-control" placeholder="Contoh: 081234567890" required>
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">NIK</label>
            <input type="text" name="walkin_nik" class="form-control" placeholder="16 Digit NIK KTP" required maxlength="16">
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Daftarkan Pasien</button>
            <a class="btn btn-danger" href="<?= $base ?>/admin">Kembali ke Dashboard</a>
        </div>
        
    </form>
</div>