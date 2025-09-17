<nav>
    <?php if (isset($_SESSION['usuario_id'])) : ?>
        <a href="/logout">Deslogar</a>
        <a href="/post/criar">Criar post</a>
    <?php endif; ?>
</nav>