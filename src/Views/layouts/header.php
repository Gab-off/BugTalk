<header>
    <nav class="">
        <?php if (isset($_SESSION['id_usuario'])) : ?>
            <div class="buttons_div">
                <a class="header-button user" href="/"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></a>
                <a class="header-button login" href="/logout">Deslogar</a>
                    <?php if ($_SERVER['REQUEST_URI'] !== '/post/criar') : ?>
                        <a class="header-button create-post" href="/post/criar">Post</a>
                    <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="buttons_div">
                <a class="header-button user" href="/login">Login</a>
                <a class="header-button login" href="/cadastro">Cadastrar-se</a>
            </div>
        <?php endif; ?>
            <div class="logo_div">
                <img class="logo" src="/assets/imgs/logotipo_blackTheme.svg" alt="">
            </div>
            <form class="form-search" action="/">
                <div class="search_wrapper">
                    <img class="search_icon" src="/assets/imgs/icons/lupa.svg" alt="">
                    <input type="text" class="search_bar">
                </div>
            </form>
    </nav>
</header>