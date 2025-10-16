<?php
namespace App\Controllers;

use App\Models\Comentario;
use App\Core\AuthGuard;

class ComentarioController {
    use AuthGuard;
    public function criar() {
        $this->checkAuth();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }

        $id_pai = (int)($_POST['id_pai'] ?? 0);
        $tipoPai = trim($_POST['tipo_pai'] ?? '');
        $texto = trim($_POST['texto'] ?? '');

        if ($id_pai > 0 && !empty($tipoPai) && !empty($texto)) {
            $comentarioModel = new Comentario();
            $comentarioModel->create([
                'id_usuario' => $_SESSION['id_usuario'],
                'id_pai' => $id_pai,
                'texto' => $texto
            ], $tipoPai);
        }

        // Redireciona de volta para a página de onde o comentário foi enviado
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit();
    }
}