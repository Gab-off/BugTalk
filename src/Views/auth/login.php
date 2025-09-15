<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<body class="cadastrar_bg">
<main class="container main_form">
    <div class="form_container">
        <h1>Login</h1>
        <?php if (isset($erro)): ?>
            <div><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form action="/login" method="post">
            <div>
                <input type="email" name="email" placeholder="Email" required class="form_input">
            </div>
            <div>
                <input type="password" name="senha" placeholder="Senha" required class="form_input">
            </div>
            <button type="submit" class="form-button">Entrar</button>
            <p>
                Não tem uma conta? <a href="/cadastro">Cadastre-se</a>
            </p>
        </form>
    </div>
</main>
</body>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
