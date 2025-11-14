<?php
namespace App\Controllers;

use App\Core\AuthGuard;
use App\Models\Comentario;

class ComentarioController
{
    use AuthGuard;

    public function criar()
    {
        $this->checkAuth();

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit;
        }

        $id_pai = (int)($_POST['id_pai'] ?? 0);
        $tipo_pai = trim($_POST['tipo_pai'] ?? '');
        $texto = trim($_POST['texto'] ?? '');

        if ($id_pai > 0 && !empty($tipo_pai) && !empty($texto)) {
            $comentarioModel = new Comentario();
            $comentarioModel->create([
                'id_usuario' => $_SESSION['id_usuario'],
                'id_pai' => $id_pai,
                'texto' => $texto
            ], $tipo_pai);
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }
}

