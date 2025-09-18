<nav class="">
    <?php if (isset($_SESSION['id_usuario'])) : ?>
        <div class="buttons_div">
            <a class="header-button user" href="/"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></a>
            <a class="header-button login" href="/logout">Deslogar</a>
        </div>
        <div class="logo_div">
            <img class="logo" src="/assets/imgs/logotipo_blackTheme.svg" alt="">
        </div>
        <!--    Reposicionar isso para um botão no corpo principal da view de todas postagens-->
        <!--        --><?php //if ($_SERVER['REQUEST_URI'] !== '/post/criar') : ?>
        <!--            <a href="/post/criar">Criar post</a>-->
        <!--        --><?php //endif; ?>
        <form class="form-search" action="/">
            <div class="search_wrapper">
                <img class="search_icon" src="/assets/imgs/icons/lupa.svg" alt="">
                <input type="text" class="search_bar">
            </div>
        </form>
    <?php endif; ?>
</nav>