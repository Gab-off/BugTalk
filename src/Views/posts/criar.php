<?php
// criar.php - Versão nova com Tailwind + CodeMirror + Tags dinâmicas + Tema Claro/Escuro
use App\Models\Tag;
require_once __DIR__ . '/../layouts/head-criar-post.php';
require_once __DIR__ . '/../layouts/header.php';

// Buscar tags do banco (você ajusta conforme seu modelo)
$tagModel = new Tag();

// Se houver erro de criação
$erro = $_GET['erro'] ?? null;
?>

<style>
    :root {
        --bg-gradient-start: #E0F7FA;
        --bg-gradient-end: #80DEEA;
        --card-bg: #FFFFFF;
        --text-primary: #006064;
        --text-secondary: #00838F;
        --border-color: #26C6DA;
        --input-bg: #F1F9FA;
        --button-gradient-start: #26C6DA;
        --button-gradient-end: #00ACC1;
        --tag-bg: #E0F7FA;
        --tag-hover: #B2EBF2;
        --error-bg: #FFEBEE;
        --error-border: #EF5350;
        --error-text: #C62828;
    }

    [data-theme="dark"] {
        --bg-gradient-start: #01090B;
        --bg-gradient-end: #003842;
        --card-bg: #0F172A;
        --text-primary: #FFFFFF;
        --text-secondary: #67E8F9;
        --border-color: #22D3EE;
        --input-bg: #1E293B;
        --button-gradient-start: #0E7490;
        --button-gradient-end: #164E63;
        --tag-bg: #1E293B;
        --tag-hover: #334155;
        --error-bg: rgba(127, 29, 29, 0.3);
        --error-border: #EF4444;
        --error-text: #FCA5A5;
    }

    body {
        background: linear-gradient(to bottom, var(--bg-gradient-start), var(--bg-gradient-end));
        transition: background 0.3s ease;
    }

    .form-card {
        background: var(--card-bg);
        border-color: var(--border-color);
        transition: all 0.3s ease;
    }

    .form-input {
        background: var(--input-bg);
        color: var(--text-primary);
        border-color: var(--border-color);
        transition: all 0.3s ease;
    }

    .form-input::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--border-color);
        box-shadow: 0 0 0 3px rgba(38, 198, 218, 0.1);
    }

    .tag-checkbox {
        background: var(--tag-bg);
        border-color: var(--border-color);
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .tag-checkbox:hover {
        background: var(--tag-hover);
    }

    .tag-checkbox input:checked + span {
        font-weight: 600;
    }

    .theme-toggle {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: var(--card-bg);
        border: 2px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .theme-toggle:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(to bottom, var(--button-gradient-start), var(--button-gradient-end));
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(38, 198, 218, 0.3);
    }

    .btn-secondary {
        border-color: var(--border-color);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: var(--input-bg);
    }

    .error-box {
        background: var(--error-bg);
        border-color: var(--error-border);
        color: var(--error-text);
    }

    .form-label {
        color: var(--text-secondary);
    }

    .form-title {
        color: var(--text-primary);
    }

    /* Estilo para o CodeMirror */
    .CodeMirror {
        border-radius: 0.75rem;
        border: 2px solid var(--border-color);
        background: var(--input-bg);
        transition: all 0.3s ease;
    }
</style>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12" data-theme="dark">


<main class="flex flex-col items-center justify-center min-h-screen px-2 py-8">
    <form class="w-full max-w-2xl rounded-xl border-2 form-card p-6 shadow-2xl flex flex-col gap-6"
          action="/post/criar" method="POST" enctype="multipart/form-data">

        <h2 class="text-3xl font-bold text-center mb-2 tracking-widest form-title">Criar Post</h2>

        <!-- Mostrar erro se houver -->
        <?php if ($erro): ?>
            <div class="error-box border rounded-lg p-3">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <!-- TÍTULO -->
        <div>
            <input type="text" id="titulo" placeholder="Título do post" name="titulo" required
                   class="form-input w-full p-3 rounded-lg border-2 text-lg font-semibold"/>
        </div>

        <!-- TEXTO DO POST -->
        <div>
            <textarea id="texto" name="texto" placeholder="Descreva seu problema ou compartilhe sua solução..." required rows="4"
                      class="form-input w-full p-3 rounded-lg border-2 text-base resize-none"></textarea>
        </div>

        <!-- IMAGEM -->
        <div>
            <label for="imagem" class="form-label text-lg font-semibold block mb-2">📸 Imagem (opcional)</label>
            <div class="flex flex-col gap-2">
                <input type="file" id="imagem" name="imagem" accept="image/*"
                       class="file:px-4 file:py-2 file:rounded-lg file:border-0 file:text-sm file:font-semibold
                              file:cursor-pointer form-input border-2 rounded-lg w-full"
                       style="file:background: var(--button-gradient-start); file:color: white;"
                       onchange="previewFileName(this)">
                <div id="nome-arquivo" class="text-sm form-label min-h-[20px] py-1 font-medium"></div>
            </div>
        </div>

        <!-- TAGS -->
        <div>
            <label class="form-label text-lg font-semibold block mb-3">🏷️ Tags da Postagem</label>
            <fieldset class="flex flex-wrap gap-2">
                <?php if (empty($tags)): ?>
                    <p class="form-label">Nenhuma tag disponível</p>
                <?php else: ?>
                    <?php foreach ($tags as $tag): ?>
                        <label class="tag-checkbox flex items-center gap-2 border-2 rounded-full px-4 py-2 cursor-pointer">
                            <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['id']) ?>"
                                   class="accent-cyan-400 w-4 h-4 rounded transition"/>
                            <span><?= htmlspecialchars($tag['nome']) ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </fieldset>
        </div>

        <!-- LINGUAGEM DO CÓDIGO -->
        <div>
            <label for="language" class="form-label text-lg font-semibold block mb-2">💻 Linguagem do Código</label>
            <select id="language" name="language"
                    class="form-input mb-3 p-3 rounded-lg border-2 font-mono w-full font-semibold">
                <option value="javascript">JavaScript</option>
                <option value="php">PHP</option>
                <option value="python">Python</option>
                <option value="css">CSS</option>
                <option value="htmlmixed">HTML</option>
            </select>
            <label for="codigo" class="form-label text-lg font-semibold block mb-2">📝 Código</label>
            <textarea id="codigo" name="codigo"></textarea>
        </div>

        <!-- BOTÕES -->
        <div class="flex gap-3 justify-end flex-col sm:flex-row">
            <a href="/" class="btn-secondary px-6 py-3 rounded-lg border-2 text-center hover:bg-cyan-950 transition font-semibold">
                Cancelar
            </a>
            <button type="submit" class="btn-primary text-white py-3 px-8 rounded-lg font-bold transition shadow-lg">
                🚀 Publicar Post
            </button>
        </div>
    </form>
</main>

<script>
    // Sistema de tema
    function toggleTheme() {
        const body = document.body;
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        // Atualizar texto do botão
        const themeIcon = document.getElementById('theme-icon');
        const themeText = document.getElementById('theme-text');

        if (newTheme === 'dark') {
            themeIcon.textContent = '☀️';
            themeText.textContent = 'Tema Claro';
            editor.setOption('theme', 'dracula');
        } else {
            themeIcon.textContent = '🌙';
            themeText.textContent = 'Tema Escuro';
            editor.setOption('theme', 'elegant');
        }
    }

    // Carregar tema salvo
    window.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.body.setAttribute('data-theme', savedTheme);

        const themeIcon = document.getElementById('theme-icon');
        const themeText = document.getElementById('theme-text');

        if (savedTheme === 'light') {
            themeIcon.textContent = '🌙';
            themeText.textContent = 'Tema Escuro';
        }
    });

    // CodeMirror Editor
    var editor = CodeMirror.fromTextArea(document.getElementById('codigo'), {
        lineNumbers: true,
        mode: "javascript",
        theme: "dracula",
        indentUnit: 2,
        indentWithTabs: true,
        styleActiveLine: true,
        matchBrackets: true,
        autofocus: false,
    });

    // Visual integrado ao BugTalk
    editor.getWrapperElement().style.fontSize = '1.1rem';
    editor.setSize(null, 300);

    // Troca automática de linguagem
    document.getElementById('language').addEventListener('change', function() {
        editor.setOption('mode', this.value);
    });

    // Preview do nome do arquivo
    function previewFileName(input) {
        const nome = input.files.length ? '📎 ' + input.files[0].name : '';
        document.getElementById('nome-arquivo').textContent = nome;
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>