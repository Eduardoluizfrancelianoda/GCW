<?php
session_start();
require_once "operações/conexao.php";

// Verifica se o usuário está logado
$usuario_logado = isset($_SESSION['usuario_id']);
$usuario_id = $usuario_logado ? $_SESSION['usuario_id'] : null;

// Captura mensagens da sessão (opcional, para feedback)
$mensagem_erro = $_SESSION['erro_post'] ?? '';
$mensagem_sucesso = $_SESSION['msg'] ?? '';
unset($_SESSION['erro_post'], $_SESSION['msg']);

// Define o ID a ser usado na consulta (para saber se o usuário curtiu cada post)
$id_consulta = $usuario_logado ? $usuario_id : -1;

// Consulta os posts ordenados por número de curtidas (maior primeiro)
$sql = "SELECT 
            posts.*, 
            usuarios.nome, 
            usuarios.foto_perfil,
            (SELECT COUNT(*) FROM curtidas WHERE curtidas.post_id = posts.id) AS total_likes,
            (SELECT COUNT(*) FROM curtidas WHERE curtidas.post_id = posts.id AND curtidas.usuario_id = ?) > 0 AS usuario_curtiu
        FROM posts
        JOIN usuarios ON posts.usuario_id = usuarios.id
        ORDER BY total_likes DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_consulta]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facefood - Ranking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inspiration&family=Roboto+Mono:wght@100..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/mainstyle.css">
</head>
<body>
<header>
    <img src="uploads/fotos/<?= htmlspecialchars($_SESSION['foto_perfil'] ?? 'default.jpg') ?>" alt="Foto de perfil" class="foto-perfil">
    <a href="perfil.php">bem vindo, <?= htmlspecialchars($_SESSION['nome'] ?? 'Visitante') ?></a>
    <a href="operações/logout.php"><img src="imgs/logout.png" alt="Sair" class="logout-icon"></a>
    <h1 style="font-family: 'Inspiration', cursive;" class="logo">Facefood.com</h1>
</header>
<!-- imagem do fundo sobre comida -->
<img src="imgs/textura enxadrada.jpg" alt="imgs/textura enxadrada" class="imagem-fundo-enxadrada">
<img src="imgs/imagem gostosa de comida.jpg" alt="imgs/imagem gostosa de comida" class="imagem-fundo">
<img src="imgs/textura_amadeirada.jpg" alt="imgs/textura amadeirada" class="imagem-fundo-amadeirada">
<section class="main-content-texture">
    <!-- Navbar -->
    <section class="navbar">
        <img src="gifs/slides de comidas.gif" alt="comidas gif" class="gif-comidas">
        <ul>
            <li><button><a href="mainpage.php">Início</a></button></li>
            <li><button><a href="ranking.php">Ranking</a></button></li>
        </ul>
        
        <img src="gifs/FOOD gigantesco girano.gif" alt="gif gigantesco girando" class="navbar-gif">
        <img src="gifs/salada.gif" alt="salada" class="navbar-gif">
    </section>

    <!-- Mensagens de feedback -->
    <?php if ($mensagem_erro): ?>
        <div style="color:red;"><?= htmlspecialchars($mensagem_erro) ?></div>
    <?php endif; ?>
    <?php if ($mensagem_sucesso): ?>
        <div style="color:green;"><?= htmlspecialchars($mensagem_sucesso) ?></div>
    <?php endif; ?>

    <!-- Feed de posts (ranking) -->
    <section class="feed">
        <h1 style="background-color: #fefa8c; padding: 10px;">Ranking dos Posts mais gostados</h1>
        <?php if (empty($posts)): ?>
            <p>Nenhum post ainda. Seja o primeiro a publicar!</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-header">
                        <!-- Foto de perfil do autor -->
                        <img src="uploads/fotos/<?= htmlspecialchars($post['foto_perfil'] ?? 'default.jpg') ?>" 
                             alt="Foto de perfil" class="foto-perfil">
                        <span><?= htmlspecialchars($post['nome']) ?></span>

                        <!-- Botões para o dono do post (se logado e for o autor) -->
                        <?php if ($usuario_logado && $usuario_id == $post['usuario_id']): ?>
                            <div style="margin-left: auto;">
                                <a href="atualizar_form.php?post_id=<?= $post['id'] ?>" class="btn-editar" 
                                   onclick="return confirm('Deseja editar este post?');">Editar</a>
                                   
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

                    <!-- Botão para alternar visibilidade da descrição -->
                    <button onclick="toggleReceita('<?= $post['id'] ?>', this)">Ver receita</button>
                    <p id="desc-<?= $post['id'] ?>" style="display: none;"><?= nl2br(htmlspecialchars($post['descricao'])) ?></p>

                    <!-- Descrição (inicialmente escondida) -->
                    <p class="descricao-post" id="desc-<?= $post['id'] ?>" style="display: none;">
                        <?= nl2br(htmlspecialchars($post['descricao'])) ?>
                    </p>

                    <!-- Área de curtidas -->
                    <div class="like-area">
                        <button class="btn-like <?= $post['usuario_curtiu'] ? 'liked' : '' ?>" 
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
function toggleReceita(postId, btn) {
    const desc = document.getElementById('desc-' + postId);
    if (desc.style.display === 'none') {
        desc.style.display = 'block';
        btn.innerText = 'Esconder receita';
    } else {
        desc.style.display = 'none';
        btn.innerText = 'Ver receita';
    }
}
</script>
</body>
</html>