<?php require_once __DIR__ . '/layouts/head.php'; ?>
<?php require_once __DIR__ . '/layouts/header.php'; ?>
<body>
<div>
    <?php if (empty($posts)): ?>
        <p>Nenhum post encontrado</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <!--    Link e título do post-->
            <a href="/post/ver?id=<?= $post['id'] ?>">
                <h1><?= htmlspecialchars($post['titulo']) ?></h1>
            </a>

            <!--    Mostragem da postage-->
            <p>
                <?= htmlspecialchars($post['conteudo']) ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
<?php require_once __DIR__ . '/layouts/footer.php'; ?>