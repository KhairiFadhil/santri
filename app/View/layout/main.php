<!DOCTYPE html>
  <html lang="id">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= $title ?? 'SANTRI' ?></title>
      <style>
          body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; }
          nav { background: #0a0a0a; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
          nav a { color: #fff; margin-right: 16px; text-decoration: none; font-weight: 500; }
          nav a:hover { text-decoration: underline; }
          footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; }
      </style>
  </head>
  <body>
      <nav>
          <a href="/santri-belajar/public/">Beranda</a>
          <?php if (isset($_SESSION['user'])): ?>
              <a href="/santri-belajar/public/dashboard">Dashboard</a>
              <a href="/santri-belajar/public/daftar">Daftar Antrean</a>
              <a href="/santri-belajar/public/antrean">Antrean Saya</a>
              <a href="/santri-belajar/public/riwayat">Riwayat</a>
              <a href="/santri-belajar/public/profile">Profil</a>
              <a href="/santri-belajar/public/logout">Keluar</a>
          <?php elseif (isset($_SESSION['staff'])): ?>
              <?php if (($_SESSION['staff']['role'] ?? '') === 'dokter'): ?>
                  <a href="/santri-belajar/public/dokter/loket">Loket Saya</a>
              <?php else: ?>
                  <a href="/santri-belajar/public/admin">Admin</a>
              <?php endif; ?>
              <a href="/santri-belajar/public/admin/logout">Keluar</a>
          <?php else: ?>
              <a href="/santri-belajar/public/login">Masuk</a>
              <a href="/santri-belajar/public/register">Daftar Akun</a>
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