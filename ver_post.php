<?php
session_start();
require 'conexao.php';

// 1. Pega o ID do post da URL e valida
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Post não encontrado.");
}
$id_post = (int)$_GET['id'];
$erro = '';

// 2. LÓGICA PARA ADICIONAR UM NOVO COMENTÁRIO (se o formulário for enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Apenas usuários logados podem comentar
    if (!isset($_SESSION['usuario_id'])) {
        die("Acesso negado. Faça login para comentar.");
    }

    $conteudo_comentario = trim($_POST['comentario']);
    $id_usuario = $_SESSION['usuario_id'];

    if (!empty($conteudo_comentario)) {
        // Query para criar o comentário e os dois relacionamentos de uma vez
        $query = '
            MATCH (u:Usuario) WHERE id(u) = $id_usuario
            MATCH (p:POST) WHERE id(p) = $id_post
            CREATE (u)-[:COMENTOU]->(c:Comentario {
                conteudo: $conteudo,
                data_criacao: datetime()
            })-[:É_RESPOSTA_DE]->(p)
        ';
        try {
            $client->run($query, [
                'id_usuario' => $id_usuario,
                'id_post' => $id_post,
                'conteudo' => htmlspecialchars($conteudo_comentario)
            ]);
            // Redireciona para a mesma página para mostrar o novo comentário e limpar o formulário
            header("Location: ver_post.php?id=" . $id_post);
            exit();
        } catch (\Exception $e) {
            $erro = "Erro ao adicionar comentário: " . $e->getMessage();
        }
    }
}


// 3. LÓGICA PARA BUSCAR OS DADOS E EXIBIR A PÁGINA
$post = null;
$comentarios = [];

try {
    // Query para buscar o post principal e seu autor
    $queryPost = '
        MATCH (u:Usuario)-[:PUBLICOU]->(p:Post) 
        WHERE id(p) = $id_post 
        RETURN p.titulo AS titulo, p.conteudo AS conteudo, u.nome AS autor
    ';
    $resultPost = $client->run($queryPost, ['id_post' => $id_post]);
    $recordPost = $resultPost->first();
    if ($recordPost) {
        $post = $recordPost->values();
    } else {
        die("Post não encontrado.");
    }

    // Query para buscar os comentários e seus autores
    $queryComentarios = '
        MATCH (u:Usuario)-[:COMENTOU]->(c:Comentario)-[:É_RESPOSTA_DE]->(p:Post)
        WHERE id(p) = $id_post
        RETURN c.conteudo AS conteudo, u.nome AS autor
        ORDER BY c.data_criacao ASC
    ';
    $resultComentarios = $client->run($queryComentarios, ['id_post' => $id_post]);
    foreach ($resultComentarios as $record) {
        $comentarios[] = $record->values();
    }

} catch (\Exception $e) {
    die("Erro ao carregar o post: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post[0]) // titulo ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { background-color: #0f4a5b; } </style>
</head>
<body class="text-gray-200">
<div class="container mx-auto p-8 max-w-4xl">
    <a href="index.php" class="text-cyan-400 hover:underline mb-6 inline-block"><< Voltar para o Fórum</a>

    <div class="bg-[#127a8c] p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($post[0]) // titulo ?></h1>
        <p class="text-sm text-gray-400 mb-4">Postado por: <?= htmlspecialchars($post[2]) // autor ?></p>
        <div class="text-gray-300 leading-relaxed">
            <?= nl2br(htmlspecialchars($post[1])) // conteudo ?>
        </div>
    </div>

    <div class="bg-[#127a8c] p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-3">Comentários</h2>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <form action="ver_post.php?id=<?= $id_post ?>" method="POST" class="mb-8">
                <textarea name="comentario" rows="4" required placeholder="Deixe seu comentário..." class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-cyan-500"></textarea>
                <button type="submit" class="mt-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Comentar</button>
            </form>
        <?php else: ?>
            <div class="bg-gray-700/50 text-center p-4 rounded-lg mb-8">
                <a href="login.php" class="font-bold text-cyan-400 hover:underline">Faça login</a> para deixar um comentário.
            </div>
        <?php endif; ?>

        <div class="space-y-6">
            <?php if (empty($comentarios)): ?>
                <p class="text-gray-400">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
            <?php else: ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="border-b border-gray-700 pb-4">
                        <p class="font-bold text-cyan-300 mb-1"><?= htmlspecialchars($comentario[1]) // autor ?></p>
                        <p><?= nl2br(htmlspecialchars($comentario[0])) // conteudo ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>