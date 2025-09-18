<?php require_once __DIR__ . '/layouts/head.php'; ?>
    <body class="container home_bg">
    <?php require_once __DIR__ . '/layouts/header.php'; ?>
    <div class="divided_structure">
        <article class="tags_tab">
            <div>
                <img src="" alt="">
                <h2 class="tags_title">BugTags</h2>
            </div>

            <div>
                <ul>
                    <?php if (empty($tags)): ?>
                        <p>Nenhuma tag encontrada</p>
                    <?php else: ?>
                        <?php foreach ($tags as $tag): ?>
                            <li><a href="/tags/<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
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
                        <div class="post_actions">

                            <form action="/post/vote" method="POST" class="vote-form">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="upvote-button <?php if ($post['userHasVoted']) echo 'active';?>">
                                    <svg width="35" height="35" viewBox="0 0 35 35" fill=""
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 18.8889V16.1111H6.38889V14.7222H7.77778V13.3333H9.16667V11.9444H10.5556V10.5556H11.9444V9.16667H13.3333V7.77778H14.7222V6.38889H16.1111V5H18.8889V6.38889H20.2778V7.77778H21.6667V9.16667H23.0556V10.5556H24.4444V11.9444H25.8333V13.3333H27.2222V14.7222H28.6111V16.1111H30V18.8889H23.0556V30H11.9444V18.8889H5ZM10.5556 16.1111H14.7222V27.2222H20.2778V16.1111H24.4444V14.7222H23.0556V13.3333H21.6667V11.9444H20.2778V10.5556H18.8889V9.16667H16.1111V10.5556H14.7222V11.9444H13.3333V13.3333H11.9444V14.7222H10.5556V16.1111Z"
                                              fill="currentColor"/>
                                        <path d="M5 18.8889V16.1111H6.38889V14.7222H7.77778V13.3333H9.16667V11.9444H10.5556V10.5556H11.9444V9.16667H13.3333V7.77778H14.7222V6.38889H16.1111V5H18.8889V6.38889H20.2778V7.77778H21.6667V9.16667H23.0556V10.5556H24.4444V11.9444H25.8333V13.3333H27.2222V14.7222H28.6111V16.1111H30V18.8889H23.0556V30H11.9444V18.8889H5ZM10.5556 16.1111H14.7222V27.2222H20.2778V16.1111H24.4444V14.7222H23.0556V13.3333H21.6667V11.9444H20.2778V10.5556H18.8889V9.16667H16.1111V10.5556H14.7222V11.9444H13.3333V13.3333H11.9444V14.7222H10.5556V16.1111Z"
                                              fill="black" fill-opacity="0.2"/>
                                    </svg>
                                </button>
                            </form>
                            <span class="vote-count"><?= $post['upvotes'] ?></span>
                        </div>

                        <!--                        <a class="upvote" href=""><img src="/assets/imgs/icons/upvote_icon.svg" alt=""></a>-->
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