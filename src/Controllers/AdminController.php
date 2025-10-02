<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Post;

class AdminController {

    /**
     * Este é o "guarda" de segurança. Ele será chamado no início de cada método
     * para garantir que apenas administradores acessem a página.
     */
    private function checkAdmin() {
        if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['usuario_isAdmin']) || $_SESSION['usuario_isAdmin'] !== true) {
            // Se não for um admin logado, expulsa para a página inicial
            header('Location: /');
            exit();
        }
    }

    /**
     * Mostra a página principal do admin, com a lista de todos os usuários.
     */
    public function showUsuarios() {
        $this->checkAdmin(); // Executa a verificação de segurança

        $usuarioModel = new Usuario();
        // Chama o novo método que criamos para buscar os dados
        $usuarios = $usuarioModel->findAllWithPostCount();

        // Carrega o arquivo da Visão e passa a variável $usuarios para ele
        require_once __DIR__ . '/../Views/admin/usuarios.php';
    }

    /**
     * Mostra todos os posts de um usuário específico.
     */
    // Dentro da classe App\Controllers\AdminController

    public function showUsuarioPosts() {
        $this->checkAdmin();

        $id_usuario = (int)($_GET['id'] ?? 0);

        // Se nenhum ID válido for passado, podemos parar aqui.
        if ($id_usuario < 0) { // O ID 0 é válido
            die("ID de usuário inválido.");
        }

        $usuarioModel = new Usuario();
        $postModel = new Post();

        // 1. Usa o novo método para buscar os dados do usuário.
        $usuario = $usuarioModel->findById($id_usuario);

        // 2. Busca os posts desse usuário.
        $posts = $postModel->findByUsuarioId($id_usuario);

        // 3. Carrega a View, passando as variáveis necessárias.
        require_once __DIR__ . '/../Views/admin/usuario_posts.php';
    }
}