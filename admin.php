<?php

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_isAdmin'] !== true) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';

$usuarios = [];
try {
    $result = $client->run('MATCH (n:Usuario) RETURN n.nome AS nome, n.email AS email, id(n) AS id ORDER BY n.nome');
    foreach ($result as $record) {
        $usuarios[] = ['id'=> $record->get('id'), 'nome' => $record->get('nome'), 'email' => $record->get('email')];
    }
} catch (\Exception $e) {
    $erro = "Erro ao buscar usuários: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Fórum TCC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Área Administrativa</h1>
    <div>
        <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</span>
        <a href="logout.php" class="ml-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Sair</a>
    </div>
</header>

<main class="container mx-auto p-8">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-4">Usuários Cadastrados</h2>
        <table class="w-full text-left">
            <thead>
            <tr class="border-b">
                <th class="p-2">ID</th>
                <th class="p-2">Nome</th>
                <th class="p-2">Email</th>
                <th class="p-2">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2"><?= htmlspecialchars($usuario['id']) ?></td>
                    <td class="p-2"><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td class="p-2"><?= htmlspecialchars($usuario['email']) ?></td>
                    <td class="p-2">
                        <a href="editar.php?id=<?= $usuario['id'] ?>" class="text-yellow-600 hover:underline mr-4">Editar</a>
                        <a href="excluir.php?id=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza?')" class="text-red-600 hover:underline">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
