<?php

namespace App\Controllers;

use App\Models\Post;

class PostController
{

    public function showCriarForm()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/../Views/posts/criar.php';
    }

    public function criar()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $conteudo = trim($_POST['conteudo'] ?? '');

        if (empty($titulo) || empty($conteudo)) {
            $erro = 'Título e conteúdo são obrigatórios.';
            require_once __DIR__ . '/../Views/posts/criar.php';
            return;
        }
        $postModel = new Post();
        $sucesso = $postModel->create([
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'id_usuario' => $_SESSION['id_usuario']
        ]);

        if ($sucesso) {
            header('Location: /');
            exit();
        } else {
            $erro = 'Ocorreu um erro ao criar seu post. Tente novamente.';
            require_once __DIR__ . '/../Views/posts/criar.php';
        }
    }
}