<?php
    session_start();

    require __DIR__ . "/../app/Core/AutoLoader.php";
    require __DIR__ . '/../config/app.php';
    require __DIR__ . '/../config/database.php';

    \App\Model\Antrian::bersihkanBasi();

    $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    $base = '/santri-belajar/public';
    if (str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }
    $uri = $uri === '' ? '/' : $uri;

    $routes = [
        'GET /'         => ['App\Controllers\HomeController', 'index'],

        'GET /login'    => ['App\Controllers\AuthController', 'showLogin',    'guest'],
        'POST /login'   => ['App\Controllers\AuthController', 'login',        'guest'],
        'GET /register' => ['App\Controllers\AuthController', 'showRegister', 'guest'],
        'POST /register'=> ['App\Controllers\AuthController', 'register',     'guest'],
        'GET /logout'   => ['App\Controllers\AuthController', 'logout'],

        'GET /dashboard'         => ['App\Controllers\DashboardController', 'index', 'auth'],
        'GET /daftar'            => ['App\Controllers\AntreanController', 'daftar', 'auth'],
        'POST /daftar'           => ['App\Controllers\AntreanController', 'prosesDaftar', 'auth'],
        'GET /antrean'           => ['App\Controllers\AntreanController', 'statusAntri', 'auth'],
        'POST /antrean/cancel'   => ['App\Controllers\AntreanController', 'batal', 'auth'],
        'GET /riwayat'           => ['App\Controllers\AntreanController', 'riwayat', 'auth'],

        'GET /profile'           => ['App\Controllers\ProfileController', 'index', 'auth'],
        'POST /profile'          => ['App\Controllers\ProfileController', 'update', 'auth'],
        'POST /profile/password' => ['App\Controllers\ProfileController', 'changePassword', 'auth'],

        'GET /api/queue/status' => ['App\Controllers\Api\QueueController', 'status', 'auth'],
        'GET /api/queue/live'   => ['App\Controllers\Api\QueueController', 'live'],

        'GET /admin/login'  => ['App\Controllers\Admin\AuthController', 'showLogin', 'guest'],
        'POST /admin/login' => ['App\Controllers\Admin\AuthController', 'login',     'guest'],
        'GET /admin/logout' => ['App\Controllers\Admin\AuthController', 'logout'],

        'GET /admin'       => ['App\Controllers\Admin\DashboardController', 'index', 'staff'],

        'GET /admin/antrean' => ['App\Controllers\Admin\AntreanController', 'index', 'staff'],

        'GET /admin/walkin'  => ['App\Controllers\Admin\WalkinController', 'index', 'staff'],
        'POST /admin/walkin' => ['App\Controllers\Admin\WalkinController', 'store', 'staff'],

        'GET /admin/poli'              => ['App\Controllers\Admin\PoliController', 'index', 'staff'],
        'GET /admin/poli/create'       => ['App\Controllers\Admin\PoliController', 'create', 'staff'],
        'POST /admin/poli'             => ['App\Controllers\Admin\PoliController', 'store', 'staff'],
        'GET /admin/poli/{id}/edit'    => ['App\Controllers\Admin\PoliController', 'edit', 'staff'],
        'POST /admin/poli/{id}'        => ['App\Controllers\Admin\PoliController', 'update', 'staff'],
        'POST /admin/poli/{id}/delete' => ['App\Controllers\Admin\PoliController', 'delete', 'staff'],

        'GET /admin/dokter'              => ['App\Controllers\Admin\DokterController', 'index', 'staff'],
        'GET /admin/dokter/create'       => ['App\Controllers\Admin\DokterController', 'create', 'staff'],
        'POST /admin/dokter'             => ['App\Controllers\Admin\DokterController', 'store', 'staff'],
        'GET /admin/dokter/{id}/edit'    => ['App\Controllers\Admin\DokterController', 'edit', 'staff'],
        'POST /admin/dokter/{id}'        => ['App\Controllers\Admin\DokterController', 'update', 'staff'],
        'POST /admin/dokter/{id}/delete' => ['App\Controllers\Admin\DokterController', 'delete', 'staff'],
        'POST /admin/dokter/{id}/akun'   => ['App\Controllers\Admin\DokterController', 'akun', 'staff'],

        'GET /admin/jadwal'              => ['App\Controllers\Admin\JadwalController', 'index', 'staff'],
        'POST /admin/jadwal'             => ['App\Controllers\Admin\JadwalController', 'upsert', 'staff'],
        'POST /admin/jadwal/delete'      => ['App\Controllers\Admin\JadwalController', 'delete', 'staff'],
        'POST /admin/jadwal/cuti'        => ['App\Controllers\Admin\JadwalController', 'setCuti', 'staff'],
        'POST /admin/jadwal/cuti/delete' => ['App\Controllers\Admin\JadwalController', 'batalCuti', 'staff'],

        'GET /admin/pasien'              => ['App\Controllers\Admin\PasienController', 'index', 'staff'],
        'POST /admin/pasien/{id}/delete' => ['App\Controllers\Admin\PasienController', 'delete', 'staff'],

        'GET /dokter/loket'                 => ['App\Controllers\Dokter\LoketController', 'index',       'dokter'],
        'POST /dokter/loket/{id}/call'      => ['App\Controllers\Dokter\LoketController', 'call',        'dokter'],
        'POST /dokter/loket/{id}/progress'  => ['App\Controllers\Dokter\LoketController', 'progress',    'dokter'],
        'POST /dokter/loket/{id}/done'      => ['App\Controllers\Dokter\LoketController', 'done',        'dokter'],
        'POST /dokter/loket/{id}/skip'      => ['App\Controllers\Dokter\LoketController', 'skip',        'dokter'],
        'POST /dokter/loket/{id}/done-next' => ['App\Controllers\Dokter\LoketController', 'doneAndNext', 'dokter'],
        'POST /dokter/loket/{id}/recall'    => ['App\Controllers\Dokter\LoketController', 'recall',      'dokter'],
        'POST /dokter/loket/tutup'          => ['App\Controllers\Dokter\LoketController', 'tutup',       'dokter'],
        'POST /dokter/loket/buka'           => ['App\Controllers\Dokter\LoketController', 'buka',        'dokter'],

    ];

    $matched = null;
    $params  = [];

    foreach ($routes as $key => $handler) {
        [$routeMethod, $pattern] = explode(' ', $key, 2);
        if ($routeMethod !== $method) continue;

        $regex = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $pattern);
        if (preg_match("#^{$regex}$#", $uri, $matches)) {
            $matched = $handler;
            array_shift($matches);
            $params = $matches;
            break;
        }
    }

    if ($matched === null) {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>{$method} {$uri}</p>";
        exit;
    }
    [$controllerClass, $controllerMethod] = $matched;
    if(isset($matched[2])){
        \App\Core\Middleware::run($matched[2]);
    }
    $controller = new $controllerClass();
    call_user_func_array([$controller,$controllerMethod], $params)


?>
