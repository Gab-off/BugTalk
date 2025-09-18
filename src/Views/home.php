<?php require_once __DIR__ . '/layouts/head.php'; ?>
    <body class="container home_bg">
    <?php require_once __DIR__ . '/layouts/header.php'; ?>
    <div class="divided_structure">
        <article class="tags_tab">
            <div>
                <img src="" alt="">
                <h2 class="tags_title">BugTags</h2>
            </div>
        </article>
        <main>
            <div>
                <?php if (empty($posts)): ?>
                    <p>Nenhum post encontrado</p>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post_content">
                        <!--    Link e título do post-->
                        <a href="/post/ver?id=<?= $post['id'] ?>">
                            <h1><?= htmlspecialchars($post['titulo']) ?></h1>
                        </a>

                        <!--    Mostragem do conteúdo do post -->
                        <p>
                            <?= htmlspecialchars($post['conteudo']) ?>
                        </p>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>

        <aside>
            <div>
                <h2>Fulano</h2>
                <p>Postou isso</p>
            </div>
        </aside>
    </div>
    </body>
<?php require_once __DIR__ . '/layouts/footer.php'; ?>