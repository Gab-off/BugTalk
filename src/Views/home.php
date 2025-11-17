<?php require_once __DIR__ . '/layouts/head.php'; ?>
    <body class="font-mono max-w-[1440px] mx-auto items-center  h-screen px-2 md:px-8 lg:px-12 bg-gradient-to-b from-[#01090B] to-[#003842] overflow-hidden">
    <?php require_once __DIR__ . '/layouts/header.php'; ?>
    <div class="text-white lg:mt-2 lg:grid lg:grid-cols-[200px_1fr_fit-content(400px)] lg:gap-6 lg:items-start">
        <aside class="bg-cyan-950 border border-cyan-400 rounded-md hidden px-2 lg:block">
            <form action="" id="bugtagsForm" class="">
                <fieldset class="flex flex-col gap-2">
                    <legend class="py-4 w-full mb-2 text-center border-b border-b-cyan-50 uppercase">Bugtags</legend>

                    <?php if (empty($tags)): ?>
                        <p>Nenhuma tag encontrada</p>
                    <?php else: ?>
                        <?php foreach ($tags as $tag): ?>
                            <div class="flex justify-between border-b border-cyan-500">
                                <label for="tag_<?= htmlspecialchars($tag['id']) ?>">
                                    <?= htmlspecialchars($tag['nome']) ?>
                                </label>
                                <input
                                        class="appearance-none accent-blue-200 w-5 h-5 border border-blue-500 rounded-md checked:bg-blue-200 transition checked:border-blue-700"
                                        type="checkbox"
                                        id="tag_<?= htmlspecialchars($tag['id']) ?>"
                                        name="tags[]"
                                        value="<?= htmlspecialchars($tag['id']) ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </fieldset>
            </form>
        </aside>

        <div class="flex flex-col h-[calc(100vh-100px)]">

            <section
                    class="my-2 flex items-center justify-between md:items-center md:py-4 lg:justify-center lg:py-2 gap-2 relative">
                <div>
                    <!-- TODO: Colocar o javascript para funcionar o checkbox menu-->
                    <button id="bugtagsBtn"
                            class=" text-sm px-2 py-1 md:px-4 md:py-2 border border-cyan-500 rounded-md text-cyan-400 lg:hidden
             focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-transparent
             transition">
                        <span class="hidden md:inline uppercase">bugtags</span><span
                                class="inline md:hidden">Tags</span>
                    </button>
                </div>

                <div class="">
                    <a href="#"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-none border border-cyan-500 rounded-md text-white">Recentes</a>
                    <a href="#"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-none border border-cyan-500 rounded-md text-white">Em
                        alta</a>
                    <a href="#"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-none border border-cyan-500 rounded-md text-white">Ajuda</a>
                    <a href="/post/criar"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-cyan-200 border border-cyan-500 rounded-md text-cyan-800 md:hidden">+</a>
                    <a href="/post/criar"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-cyan-200 border border-cyan-500 rounded-md text-cyan-800 hidden md:inline">Postar</a>
                </div>
            </section>
            <main class="overflow-y-auto flex-1 space-y-4 pb-8">
                <?php if (empty($posts)): ?>
                    <h2 class="t">Nenhum post encontrado</h2>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <article
                                class="grid grid-cols-[max-content_1fr_max-content] gap-4 gap-y-2 items-center
                                border-y border-y-cyan-500 md:border md:border-cyan-500 md:rounded-md p-3">

                            <!--user icon image-->
                            <!--TODO: add user page-->
                            <a href="#">
                                <img src="/assets/imgs/icons/icon_user.svg" alt="imagem do usuário" class="w-10 h-10
                                object-contain p-1 ">
                            </a>

                            <!-- name user and title post-->
                            <div class="grid md:gap-3 md:px-2">

                                <!--TODO: add user page-->
                                <a href="#">
                                    <p class="text-sm hover:underline hover:text-cyan-500 transition"><?= htmlspecialchars($post['autor']) ?></p>
                                </a>
                                <a href="/post/ver?id=<?= $post['id'] ?>">
                                    <h2 class="font-bold text-sm md:text-xl hover:underline hover:text-cyan-500
                                    transition">
                                        <?= htmlspecialchars($post['titulo']) ?>
                                    </h2>
                                </a>
                            </div>

                            <div class="flex gap-3 justify-self-end">
                                <a href="#" class="w-5 h-5 md:w-7 md:h-7"><img
                                            src="/assets/imgs/icons/share_icon_dark.svg"
                                            alt="ícone de compartilhar post"></a>
                                <a href="#" class="w-5 h-5 md:w-7 md:h-7"><img
                                            src="/assets/imgs/icons/favorite.svg"
                                            alt="ícone de favoritar postagem"></a>
                            </div>

                            <div class="row-start-3 md:row-start-2 justify-self-center">
                                <form action="/post/vote" method="POST" class="flex flex-col items-center gap-1">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit"
                                            class="flex flex-col items-center gap-1 cursor-pointer text-sm md:text-md hover:text-cyan-400 transition-colors
                       <?php if ($post['user_has_voted']): ?>text-cyan-400 animate-pulse<?php endif; ?>">
                                        <img src="/assets/imgs/icons/upvote_icon.svg"
                                             alt="seta apontada para cima para dar upvote"
                                             class="w-6 h-6">
                                        <span><?= $post['upvotes'] ?></span>
                                    </button>
                                </form>
                            </div>


                            <div class="col-span-3 md:col-span-2 bg-[#092A2F] border border-cyan-500 rounded-md">
                                <a href="">
                                    <p class="text-sm md:text-lg text-white line-clamp-6 px-2 py-1">
                                        <?= htmlspecialchars($post['conteudo']) ?>
                                    </p>
                                </a>
                            </div>

                            <div class="col-start-2">
                                <?php if (!empty($post['tags'])): ?>
                                    <?php foreach ($post['tags'] as $tagName): ?>
                                        <!--TODO: (talvez) estruturar o link para fazer pela tag clicada-->
                                        <a href="#"
                                           class=" rounded-2xl border border-cyan-700 p-1 text-xs lowercase"><?= htmlspecialchars($tagName) ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-4 px-2 pt-2 border-t border-cyan-500/30 col-start-3">
                                <a href="/post/ver?id=<?= $post['id'] ?>"
                                   class="flex items-center gap-2 hover:text-cyan-400 transition-colors">
                                    <img src="/assets/imgs/icons/comment.svg" alt="Comentar" class="w-5 h-5">
                                    <!-- TODO: coletar os dados para mostrar-->
                                    <span class="text-sm">12 <span class="hidden md:inline">comentários</span></span>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </main>
        </div>

        <div class="hidden border rounded-md border-cyan-300 lg:block lg:justify-self-end px-2 py-4">
            <h2 class="text-center mb-4 text-cyan-300 font-semibold">Atividades recentes</h2>
            <?php if (empty($atividades)): ?>
                <p class="text-cyan-100 text-sm">Nenhuma atividade recente</p>
            <?php else: ?>
                <?php foreach ($atividades as $atividade): ?>
                    <div class="grid grid-cols-[50px_1fr] gap-2 border mb-2 border-cyan-300 rounded p-2 hover:bg-cyan-950/30 transition">
                        <div class="flex items-center justify-center bg-cyan-500 rounded overflow-hidden">
                            <img src="/assets/imgs/icons/icon_user.svg" alt="avatar" class="w-full h-full object-cover">
                        </div>

                        <div class="text-xs px-1 text-cyan-100">
                            <p class="">
                                <a href="/post/ver?id=<?= $atividade['id_alvo'] ?>"
                                   class="text-cyan-400 hover:underline">
                                    <?= htmlspecialchars($atividade['autor']) ?>
                                </a>
                            </p>
                            <p class="text-cyan-200">
                                <?= htmlspecialchars($atividade['tipo_evento']) ?>
                            </p>
                            <p class="text-cyan-100 truncate">
                                "<?= htmlspecialchars(substr($atividade['titulo_alvo'], 0, 30)) ?>..."
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
    </body>
<?php require_once __DIR__ . '/layouts/footer.php'; ?>