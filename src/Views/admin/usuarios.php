<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<body class="admin-page-bg">
<main class="container admin-container">
    <h1 class="admin-title">Gerenciamento de Usuários</h1>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Nº de Posts</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['postCount']) ?></td>
                    <td>
                        <a href="/admin/usuario/posts?id=<?= $usuario['id'] ?>" class="link-acao">Ver Posts</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
