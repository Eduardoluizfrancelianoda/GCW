<?php
session_start();
require_once "operações/conexao.php";

// Verifica se o usuário está logado (para ações que dependem de login)
$usuario_logado = isset($_SESSION['usuario_id']);
$usuario_id = $usuario_logado ? $_SESSION['usuario_id'] : null;

// Exibe mensagens da sessão (de criar_post.php, etc.)
$mensagem_erro = '';
$mensagem_sucesso = '';

if (isset($_SESSION['erro_post'])) {
    $mensagem_erro = $_SESSION['erro_post'];
    unset($_SESSION['erro_post']);
}
if (isset($_SESSION['msg'])) {
    $mensagem_sucesso = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Consulta os posts com informações do autor e total de curtidas
$sql = "SELECT posts.*, usuarios.nome, usuarios.foto_perfil,
        (SELECT COUNT(*) FROM curtidas WHERE curtidas.post_id = posts.id) AS total_likes
        FROM posts
        JOIN usuarios ON posts.usuario_id = usuarios.id
        ORDER BY posts.id DESC";
        // Para produção, adicionar LIMIT com paginação: LIMIT :offset, :limit

$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facefood - Início</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inspiration&family=Roboto+Mono:wght@100..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/mainstyle.css">
</head>
<body>
<header>
    <a href="operações/logout.php"><img src="imgs/logout.png" alt="Sair" class="logout-icon"></a>
    <h1 style="font-family: 'Inspiration', cursive;" class="logo">Facefood.com</h1>
</header>

<section class="main-content-texture">
    <!-- Navbar -->
    <section class="navbar">
        <img src="gifs/slides de comidas.gif" alt="comidas gif" class="gif-comidas">
        <ul>
            <li><a href="mainpage.php">Início</a></li>
            <li><a href="ranking.php">ranking</a></li>
            <li><a href="perfil.php">Perfil</a></li>
        </ul>
    </section>

    <!-- Mensagens de feedback -->
    <?php if ($mensagem_erro): ?>
        <div style="color:red;"><?= htmlspecialchars($mensagem_erro) ?></div>
    <?php endif; ?>
    <?php if ($mensagem_sucesso): ?>
        <div style="color:green;"><?= htmlspecialchars($mensagem_sucesso) ?></div>
    <?php endif; ?>

    <!-- Formulário de criação de post (visível apenas para usuários logados) -->
    <?php if ($usuario_logado): ?>
    <section class="criar-post">
        <form action="operações/criar_post.php" method="POST" enctype="multipart/form-data" class="form-criar-post">
            <label for="input-imagem">Imagem da publicação:</label>
            <input type="file" name="imagem" accept="image/*" id="input-imagem" class="input-imagem" required>
            <hr>
            <input type="text" name="titulo" placeholder="Título da receita" class="input-titulo" required>
            <textarea name="legenda" placeholder="Escreva a legenda..." class="input-legenda" required></textarea>
            <button type="submit">Publicar</button>
            <img src="gifs/coxinha.gif" alt="Coxinha">
        </form>
    </section>
    <?php else: ?>
        <p><a href="login.php">Faça login</a> para criar um post.</p>
    <?php endif; ?>

    <!-- Feed de posts -->
    <section class="feed">
        <?php if (empty($posts)): ?>
            <p>Nenhum post ainda. Seja o primeiro a publicar!</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-header">
                        <!-- Foto de perfil com caminho finalmente corrigido (graças a Deus, abençoe este código e os meus projetos, você não faz ideia de quanta dor de cabeça isso me deu) -->
                        <img src="uploads/fotos/<?= htmlspecialchars($post['foto_perfil'] ?? 'default.jpg') ?>" 
                             alt="Foto de perfil" class="foto-perfil">
                        <span><?= htmlspecialchars($post['nome']) ?></span>

                        <!-- Botões para o dono do post -->
                        <?php if ($usuario_logado && $usuario_id == $post['usuario_id']): ?>
                            <div style="margin-left: auto;">
                                <!-- Botão editar: link para página de edição (GET) -->
                                <a href="atualizar_form.php?post_id=<?= $post['id'] ?>" class="btn-editar" 
                                   onclick="return confirm('Deseja editar este post?');">Editar</a>

                                <!-- Botão deletar: formulário POST -->
                                <form method="POST" action="crud/deletar.php" style="display:inline;">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja deletar este post?');" 
                                            class="btn-deletar">Deletar</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Imagem do post -->
                    <?php if (!empty($post['imagem'])): ?>
                        <img src="uploads/posts/<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post" class="imagem-post">
                    <?php endif; ?>

                    <h2><?= htmlspecialchars($post['titulo']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($post['descricao'])) ?></p>

                    <!-- Área de curtidas -->
                    <div class="like-area">
                        <button class="btn-like <?= ($usuario_logado && $usuario_curtiu ?? false) ? 'liked' : '' ?>" 
                                data-post-id="<?= $post['id'] ?>" 
                                <?= $usuario_logado ? '' : 'disabled title="Faça login para curtir"' ?>>
                            👍 <span class="like-count"><?= (int)$post['total_likes'] ?></span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</section>

<script>
document.querySelectorAll('.btn-like').forEach(button => {
    button.addEventListener('click', function() {
        // Se o botão estiver desabilitado (não logado), não faz nada
        if (this.disabled) return;

        const postId = this.dataset.postId;
        const likeCountSpan = this.querySelector('.like-count');

        fetch('operações/like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                likeCountSpan.textContent = data.total_likes;
                if (data.liked) {
                    this.classList.add('liked');
                } else {
                    this.classList.remove('liked');
                }
            } else {
                // Se não logado, redireciona para login ou exibe mensagem
                alert('Você precisa estar logado para curtir.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    });
});
</script>
</body>
</html>