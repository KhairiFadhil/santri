<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SANTRI' ?></title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    
    <style>
        /* I kept your original internal styles just in case, but you can 
           remove these if they conflict with your global style.css */
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; }
        nav { background: #0a0a0a; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        nav a { color: #fff; margin-right: 16px; text-decoration: none; font-weight: 500; }
        nav a:hover { text-decoration: underline; }
        footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; }
    </style>
</head>
<body>
    <nav>
        <a href="<?= BASE_URL ?>/">Beranda</a>
        
        <?php if (isset($_SESSION['user'])): ?>
            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
            <a href="<?= BASE_URL ?>/daftar">Daftar Antrean</a>
            <a href="<?= BASE_URL ?>/antrean">Antrean Saya</a>
            <a href="<?= BASE_URL ?>/riwayat">Riwayat</a>
            <a href="<?= BASE_URL ?>/profile">Profil</a>
            <a href="<?= BASE_URL ?>/logout">Keluar</a>
            
        <?php elseif (isset($_SESSION['staff'])): ?>
            <?php if (($_SESSION['staff']['role'] ?? '') === 'dokter'): ?>
                <a href="<?= BASE_URL ?>/dokter/loket">Loket Saya</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/admin">Admin</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/admin/logout">Keluar</a>
            
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login">Masuk</a>
            <a href="<?= BASE_URL ?>/register">Daftar Akun</a>
        <?php endif; ?>
    </nav>

    <main>
        <?= $content ?>      
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> RS Medicaria · SANTRI</p>
    </footer>
</body>
</html>