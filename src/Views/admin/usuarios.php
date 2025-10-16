<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<body class="admin-page-bg">
<main class="container admin-container">
    <?php require_once __DIR__ . '/../layouts/header.php'; ?>
    <h1 class="admin-title">Gerenciamento de Usuários</h1>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Posts</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr class="<?= $usuario['isBanned'] ? 'status-banned' : '' ?> <?= $usuario['timeoutUntil'] ? 'status-timeout' : '' ?> <?= ($usuario['id'] === $id_admin_logado) ? 'self-admin-row' : '' ?>">
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td>
                        <?= htmlspecialchars($usuario['nome']) ?>
                        <?php if ($usuario['id'] === $id_admin_logado) : ?>
                            <span class="self-tag">(Você)</span> <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['postCount']) ?></td>
                    <td>
                        <?php if ($usuario['isBanned']): ?>
                            <span class="status-label banned">Banido</span>
                        <?php elseif ($usuario['timeoutUntil']): ?>
                            <span class="status-label timeout">Timeout</span>
                        <?php else: ?>
                            <span class="status-label active">Ativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="acoes-admin">
                        <a href="/admin/usuario/posts?id=<?= $usuario['id'] ?>" class="link-acao">Ver Posts</a>

                        <?php if ($usuario['id'] !== $id_admin_logado) : ?>
                            <?php if ($usuario['isBanned'] || $usuario['timeoutUntil']): ?>
                                <a href="/admin/usuario/pardon?id=<?= $usuario['id'] ?>" class="link-acao pardon">Perdoar</a>
                            <?php else: ?>
                                <a href="/admin/usuario/timeout?id=<?= $usuario['id'] ?>" class="link-acao timeout"
                                   onclick="return confirm('Aplicar timeout de 24h neste usuário?')">Timeout</a>
                                <a href="/admin/usuario/ban?id=<?= $usuario['id'] ?>" class="link-acao ban"
                                   onclick="return confirm('Banir este usuário permanentemente?')">Banir</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
