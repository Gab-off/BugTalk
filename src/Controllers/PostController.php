<?php

namespace App\Controllers;

use App\Core\AuthGuard;
use App\Models\Post;
use App\Models\Tag;

class PostController
{
    use AuthGuard;

    public function showCriarForm()
    {
        $this->checkAuth();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }

        // Buscar tags disponíveis
        $tagModel = new Tag();
        $tags = $tagModel->findAll();

        require_once __DIR__ . '/../Views/posts/criar.php';
    }

    public function criar()
    {
        $this->checkAuth();

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /post/criar');
            exit;
        }

        // Capturar dados
        $titulo = trim($_POST['titulo'] ?? '');
        $texto = trim($_POST['texto'] ?? '');
        $codigo = trim($_POST['codigo'] ?? '');
        $linguagem = $_POST['language'] ?? 'javascript';
        $tag_ids = $_POST['tags'] ?? [];
        $id_usuario = $_SESSION['id_usuario'];
        $erro = null;

        // Validar campos obrigatórios
        if (empty($titulo) || empty($texto)) {
            $erro = 'Título e conteúdo são obrigatórios.';
            $tagModel = new Tag();
            $tags = $tagModel->findAll();
            require_once __DIR__ . '/../Views/posts/criar.php';
            return;
        }

        // Por enquanto, imagem fica como null
        $imagem_url = null;

        // Criar post
        $postModel = new Post();
        $id_post_criado = $postModel->create([
            'titulo' => $titulo,
            'conteudo' => $texto,
            'codigo' => $codigo,
            'linguagem' => $linguagem,
            'imagem_url' => $imagem_url,
            'id_usuario' => $id_usuario,
            'tag_ids' => $tag_ids
        ]);

        if ($id_post_criado !== null) {
            header('Location: /post/ver?id=' . $id_post_criado);
            exit;
        } else {
            $erro = 'Ocorreu um erro ao criar seu post. Tente novamente.';
            $tagModel = new Tag();
            $tags = $tagModel->findAll();
            require_once __DIR__ . '/../Views/posts/criar.php';
            return;
        }
    }

    public function vote()
    {
        $this->checkAuth();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }

        $id_post = (int)($_POST['post_id'] ?? 0);
        $id_usuario = $_SESSION['id_usuario'];

        if ($id_post > 0) {
            $postModel = new Post();
            $postModel->toggleVote($id_post, $id_usuario);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');;
        exit();
    }

    // Dentro da classe App\Controllers\PostController

// Você já tem um método findById no seu Post.php, vamos usá-lo.
// Se não tiver, crie um método simples que busca um post pelo ID.
    /**
     * Exibe um único post e seus comentários.
     */
    public function show()
    {
        $id_post = (int)($_GET['id'] ?? 0);
        $id_usuario_logado = $_SESSION['id_usuario'] ?? null;  // ← ADICIONE ISSO

        $postModel = new Post();
        $post = $postModel->findById($id_post, $id_usuario_logado);  // ← PASSE AQUI

        if (!$post) {
            http_response_code(404);
            echo "Post não encontrado.";
            exit();
        }

        $comentarioModel = new \App\Models\Comentario();
        $comentarios = $comentarioModel->findComentariosAninhados($id_post);

        require_once __DIR__ . '/../Views/posts/ver.php';
    }
}