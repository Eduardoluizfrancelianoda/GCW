<?php
session_start();
require_once "operações/conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = '';

// ==================== PROCESSAR DELEÇÃO DA CONTA ====================
if (isset($_POST['delete_account'])) {
    // Confirmação via JavaScript já foi feita no front-end, mas vamos validar novamente
    // Busca informações do usuário para deletar a foto de perfil e imagens dos posts
    $stmt = $pdo->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Busca todos os posts do usuário para obter as imagens
    $stmtPosts = $pdo->prepare("SELECT imagem FROM posts WHERE usuario_id = ?");
    $stmtPosts->execute([$usuario_id]);
    $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);

    // Deleta a foto de perfil (se não for nula e existir)
    if (!empty($usuario['foto_perfil'])) {
        $caminho_foto = 'uploads/fotos/' . $usuario['foto_perfil'];
        if (file_exists($caminho_foto)) {
            unlink($caminho_foto);
        }
    }

    // Deleta todas as imagens dos posts
    foreach ($posts as $post) {
        if (!empty($post['imagem'])) {
            $caminho_imagem = 'uploads/posts/' . $post['imagem'];
            if (file_exists($caminho_imagem)) {
                unlink($caminho_imagem);
            }
        }
    }

    // Deleta o usuário do banco (posts e curtidas serão removidos automaticamente devido ao ON DELETE CASCADE)
    $stmtDelete = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmtDelete->execute([$usuario_id]);

    // Destroi a sessão e redireciona para a página inicial
    session_destroy();
    header('Location: mainpage.php'); // ou login.php
    exit;
}

// ==================== PROCESSAR ATUALIZAÇÃO DO PERFIL ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_profile'])) {
    $novo_nome = trim($_POST['nome']);
    $foto_atual = $_POST['foto_atual'] ?? '';

    if (empty($novo_nome)) {
        $mensagem = '<p style="color:red;">O nome não pode estar vazio.</p>';
    } else {
        // Processa upload da nova foto, se enviada
        $nome_foto = $foto_atual; // mantém a atual por padrão
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $arquivo = $_FILES['foto_perfil'];
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extensao, $extensoes_permitidas)) {
                $mensagem = '<p style="color:red;">Apenas arquivos JPG, JPEG, PNG ou GIF são permitidos.</p>';
            } elseif ($arquivo['size'] > 2 * 1024 * 1024) { // 2MB
                $mensagem = '<p style="color:red;">A imagem deve ter no máximo 2MB.</p>';
            } else {
                // Verifica MIME type real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $arquivo['tmp_name']);
                finfo_close($finfo);
                $mimes_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($mime, $mimes_permitidos)) {
                    $mensagem = '<p style="color:red;">Tipo de arquivo inválido.</p>';
                } else {
                    // Cria diretório se não existir
                    $diretorio = 'uploads/fotos/';
                    if (!is_dir($diretorio)) {
                        mkdir($diretorio, 0755, true);
                    }

                    // Gera nome único
                    $nome_arquivo = uniqid('perfil_', true) . '.' . $extensao;
                    $caminho = $diretorio . $nome_arquivo;

                    if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                        // Remove foto antiga se não for a padrão e existir
                        if ($foto_atual && file_exists($diretorio . $foto_atual)) {
                            unlink($diretorio . $foto_atual);
                        }
                        $nome_foto = $nome_arquivo;
                    } else {
                        $mensagem = '<p style="color:red;">Erro ao salvar a imagem.</p>';
                    }
                }
            }
        }

        // Se não houve erro, atualiza o banco
        if (empty($mensagem)) {
            $sql = "UPDATE usuarios SET nome = ?, foto_perfil = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$novo_nome, $nome_foto, $usuario_id])) {
                // Atualiza o nome na sessão
                $_SESSION['nome'] = $novo_nome;
                $_SESSION['success'] = 'Perfil atualizado com sucesso!';
                header('Location: perfil.php');
                exit;
            } else {
                $mensagem = '<p style="color:red;">Erro ao atualizar o perfil.</p>';
            }
        }
    }
}

// Exibe mensagem de sucesso armazenada na sessão
if (isset($_SESSION['success'])) {
    $mensagem = '<p style="color:green;">' . htmlspecialchars($_SESSION['success']) . '</p>';
    unset($_SESSION['success']);
}

// Busca dados atuais do usuário
$sql = "SELECT nome, foto_perfil FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die('Usuário não encontrado.');
}

// Busca posts do usuário com total de curtidas
$sql_posts = "SELECT posts.*, 
              (SELECT COUNT(*) FROM curtidas WHERE curtidas.post_id = posts.id) AS total_likes
              FROM posts
              WHERE posts.usuario_id = ?
              ORDER BY posts.id DESC";
$stmt_posts = $pdo->prepare($sql_posts);
$stmt_posts->execute([$usuario_id]);
$posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facefood - Perfil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inspiration&family=Roboto+Mono:wght@100..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/mainstyle.css">
</head>
<body>
<header>
    <a href="perfil.php">bem vindo, <?= htmlspecialchars($_SESSION['nome'] ?? 'Visitante') ?></a>
    <a href="operações/logout.php"><img src="imgs/logout.png" alt="Sair" class="logout-icon"></a>
    <h1 style="font-family: 'Inspiration', cursive;" class="logo">Facefood.com</h1>
</header>

<!-- imagem do fundo sobre comida -->
<img src="imgs/textura enxadrada.jpg" alt="imgs/textura enxadrada" class="imagem-fundo-enxadrada">
<img src="imgs/textura_amadeirada.jpg" alt="imgs/textura amadeirada" class="imagem-fundo-amadeirada">

<section class="main-content-texture">
    <!-- Navbar -->
    <section class="navbar">
        <img src="gifs/slides de comidas.gif" alt="comidas gif" class="gif-comidas">
        <ul>
            <li><button><a href="mainpage.php">Início</a></button></li>
            <li><button><a href="ranking.php">ranking</a></button></li>
        </ul>
        
        <img src="gifs/FOOD gigantesco girano.gif" alt="gif gigantesco girando" class="navbar-gif">
        <img src="gifs/salada.gif" alt="salada" class="navbar-gif">
    </section>
    <section class="feed">
        <?= $mensagem ?>

        <!-- Exibição do perfil e formulário de edição -->
        <div class="profile-edit">
            <h2>Meu Perfil</h2>
            <form action="perfil.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="edit_profile" value="1">
                <input type="hidden" name="foto_atual" value="<?= htmlspecialchars($usuario['foto_perfil']) ?>">

                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

                <label>Foto atual:</label><br>
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="uploads/fotos/<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil" style="max-width: 150px;">
                <?php else: ?>
                    <p>Nenhuma foto definida.</p>
                <?php endif; ?>
                <br>

                <label for="foto_perfil">Nova foto (opcional):</label>
                <input type="file" name="foto_perfil" id="foto_perfil" accept="image/jpeg,image/png,image/gif">

                <button type="submit">Salvar alterações</button>
                <button type="button" onclick="window.location.href='mainpage.php';">Ir para a página principal</button>
            </form>

            <!-- Botão para deletar a conta -->
            <hr>
            <form action="perfil.php" method="post" onsubmit="return confirm('ATENÇÃO! Essa ação é irreversível. Todos os seus posts e curtidas serão perdidos. Tem certeza que deseja deletar sua conta?');">
                <input type="hidden" name="delete_account" value="1">
                <button type="submit" style="background-color:#dc3545; color:white; padding:10px 20px; cursor:pointer;">Deletar minha conta</button>
            </form>
        </div>

        <hr>

        <!-- Listagem dos posts do usuário -->
        <h3>Meus Posts</h3>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-header">
                        <img src="uploads/fotos/<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil" class="foto-perfil">
                        <span><?= htmlspecialchars($usuario['nome']) ?></span>
                    </div>
                    <h4><?= htmlspecialchars($post['titulo']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($post['descricao'])) ?></p>
                    <?php if (!empty($post['imagem'])): ?>
                        <img src="uploads/posts/<?= htmlspecialchars($post['imagem']) ?>" alt="Imagem do post" style="max-width: 100%;">
                    <?php endif; ?>
                    <div class="post-likes">
                        Curtidas: <?= (int)$post['total_likes'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você ainda não fez nenhum post.</p>
        <?php endif; ?>
    </section>
</section>
</body>
</html>