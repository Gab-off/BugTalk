<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<body class="cadastrar_bg">
<main class="container main_form">
    <div class="all_container">
        <img src="/assets/imgs/logo_bug.svg" alt="" class="logo_bug">
        <div class="form_container">
            <h1 class="form_title">Login</h1>
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
        <img src="/assets/imgs/logo_bug_inverted.svg" alt="" class="logo_bug">
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
