<nav>
    <?php if (isset($_SESSION['usuario_id'])) : ?>
        <a href="/logout">Deslogar</a>
    <?php endif; ?>
</nav>