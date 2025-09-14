<?php
session_start();

// 1. Limpa todas as variáveis da sessão
session_unset();

// 2. Destrói a sessão
session_destroy();

// 3. Redireciona para a página de login
header('Location: login.php');
exit();