<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Antrean Klinik</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
        }

        .footer-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        .login-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>Selamat Datang</h2>
            <p>Silakan masuk ke akun antrean Anda</p>
        </div>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Masukkan username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="footer-link">
            Belum punya akun? <a href="/santri-belajar/public/register">Daftar sekarang</a>
        </div>

        <div class="login-footer">
            <p>&copy; <?= date('Y'); ?> SANTRI. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>

</body>
</html>