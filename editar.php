<?php

session_start();

// O "GUARDA" DA PÁGINA
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';
$erro = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$usuarios = null;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuário inválido ou não fornecido.");
}

// Se a verificação passou, podemos pegar o valor com segurança
$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if(empty($nome) || empty($email)){
        $erro = "Nome e email são obrigatórios.";
    } else {
        $query = 'MATCH (n:Usuario) WHERE id(n) = $id SET n.nome = $nome, n.email = $email';
        try {
            $client->run($query, ['id'=> $id, 'nome' => $nome, 'email' => $email]);
            header('Location: admin.php');
            exit();
        } catch (\Exception $e) {
            $erro = "Erro ao atualizar usuário: " . $e->getMessage();
        }
    }
}

try {
    $result = $client->run('MATCH (n:Usuario) WHERE id(n) = $id RETURN n.nome AS nome, n.email AS email', ['id' => $id]);
    $record = $result->first();
    if($record) {
        $usuario = ['nome' => $record->get('nome'), 'email' => $record->get('email')];
    } else {
        die("Usuário não encontrado!");
    }
} catch (\Exception $e) {
    die("Usuário não encontrado");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
<div class="container mx-auto p-8 max-w-lg">
    <h1 class="text-3xl font-bold mb-6 text-center text-cyan-400">Editar Usuário</h1>
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <?php if ($erro): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form action="editar.php?id=<?= $id ?>" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <div class="mb-4">
                <label for="nome" class="block mb-2">Nome:</label>
                <input type="text" name="nome" id="nome" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-cyan-500" value="<?= htmlspecialchars($usuario['nome']) ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2">Email:</label>
                <input type="email" name="email" id="email" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-cyan-500" value="<?= htmlspecialchars($usuario['email']) ?>">
            </div>
            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Atualizar</button>
            <a href="admin.php" class="block text-center mt-4 text-gray-400 hover:text-white">Cancelar</a>        </form>
    </div>
</div>
</body>
</html>