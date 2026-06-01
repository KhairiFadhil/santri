<?php

namespace App\Core;

abstract class Controller
{
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function flash(string $kind, string $message): void
    {
        $_SESSION['flash'] = compact('kind', 'message');
    }
}
