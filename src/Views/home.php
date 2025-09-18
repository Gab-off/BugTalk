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
            <?php if (empty($posts)): ?>
                <p>Nenhum post encontrado</p>
            <?php else: ?>
                <div class="posts_buttons">
                    <a href="">recentes</a>
                    <a href="">Em alta</a>
                    <a href="">Pedido de ajuda</a>
                </div>
                <?php foreach ($posts as $post): ?>
                    <div class="post_content">
                        <div>
                            <img src="/assets/imgs/icons/icon_user.svg" alt="">
                        </div>
                        <div>
                            <div>
                                <p class="post_user">Nome usuário</p>
                                <!--    Link e título do post-->
                                <a href="/post/ver?id=<?= $post['id'] ?>">
                                    <h1 class="post_title"><?= htmlspecialchars($post['titulo']) ?></h1>
                                </a>
                            </div>
                            <div>
                                <a href=""></a>
                            </div>
                        </div>

                        <!--    Mostragem do conteúdo do post -->
                        <a class="upvote" href=""><img src="/assets/imgs/icons/upvote_icon.svg" alt=""></a>
                        <div class="post_container">
                            <div class="post_text">
                                <?= htmlspecialchars($post['conteudo']) ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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