<?php

namespace App\Core;

trait AuthGuard
{
    private function checkAuth()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }
    }

    private function checkAdmin()
    {
        $this->checkAuth();
        if (!isset($_SESSION['usuario_isAdmin']) || $_SESSION['usuario_isAdmin'] !== true) {
            header('Location: /');
            exit();
        }
    }
}
