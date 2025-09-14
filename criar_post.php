<?php
session_start();

if(!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);
    $id_usuario = $_SESSION['usuario_id'];

    if(empty($titulo) || empty($conteudo)) {
        $erro = "Título e comteúdo são obrigatórios.";
    } else {
        $query = '
          MATCH (u:Usuario) WHERE id(u) = $id_usuario
          CREATE (p:Post {
              titulo: $titulo, 
              conteudo: $conteudo, 
              data_criacao: datetime(),
              score: 0 
          })
          CREATE (u)-[:PUBLICOU]->(p)
        ';

        try {
            $client->run($query,['id_usuario' => $id_usuario, 'titulo' => htmlspecialchars($titulo), 'conteudo' => htmlspecialchars($conteudo)]);
            header('Location: index.php');
            exit();
        } catch (\Exception $e) {
            $erro = "Erro ao criar post: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Novo Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
<div class="container mx-auto p-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6 text-center text-cyan-400">Criar Novo Post</h1>
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <?php if ($erro): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form action="criar_post.php" method="POST">
            <div class="mb-4">
                <label for="titulo" class="block mb-2">Título:</label>
                <input type="text" name="titulo" id="titulo" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-cyan-500">
            </div>
            <div class="mb-6">
                <label for="conteudo" class="block mb-2">Conteúdo:</label>
                <textarea name="conteudo" id="conteudo" rows="6" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-cyan-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Publicar</button>
            <a href="index.php" class="block text-center mt-4 text-gray-400 hover:text-white">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
