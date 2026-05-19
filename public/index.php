<?php
    // Mulai session SEBELUM apapun (wajib untuk auth)
    session_start();

    require __DIR__ . "/../app/Core/AutoLoader.php";
    require __DIR__ . '/../config/database.php';

    $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    $base = '/santri-belajar/public';
    if (str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }
    $uri = $uri === '' ? '/' : $uri;

    // Format route: 'METHOD URI' => [Controller, method]
    $routes = [
        // Halaman publik
        'GET /'         => ['App\Controllers\HomeController',   'index'],
        'GET /about'    => ['App\Controllers\HomeController',   'about'],
        'GET /faq'      => ['App\Controllers\HomeController',   'faq'],

        // Auth (GET = tampil form, POST = proses form)
        'GET /login'    => ['App\Controllers\AuthController',   'showLogin'],
        'POST /login'   => ['App\Controllers\AuthController',   'login'],
        'GET /register' => ['App\Controllers\AuthController',   'showRegister'],
        'POST /register'=> ['App\Controllers\AuthController',   'register'],
        'GET /logout'   => ['App\Controllers\AuthController',   'logout'],

        // Dashboard (perlu login)
        'GET /dashboard' => ['App\Controllers\DashboardController', 'index'],

        // Detail dinamis
        'GET /poli/{id}'   => ['App\Controllers\PoliController',   'detail'],
        'GET /dokter/{id}' => ['App\Controllers\DokterController', 'detail'],
    ];

    $matched = null;
    $params  = [];

    foreach ($routes as $key => $handler) {
        // Pecah "GET /login" → ['GET', '/login']
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
        echo "<h1>404 Not Found</h1><p>Halaman <code>{$method} {$uri}</code> tidak ada.</p>";
        exit;
    }

    [$controllerClass, $controllerMethod] = $matched;
    $controller = new $controllerClass();
    call_user_func_array([$controller, $controllerMethod], $params);
?>
