<?php
session_start();
require_once "operações/conexao.php";

// Verifica se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

// Se for POST, processa a atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? 0;
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    // Verifica se o post pertence ao usuário
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$post_id, $usuario_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("Post não encontrado ou você não tem permissão.");
    }

    $nova_imagem = $post['imagem']; // mantém a atual por padrão

    // Upload de nova imagem, se enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem'];
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $ext_permitidas)) {
            $erro = "Apenas imagens JPG, JPEG, PNG ou GIF são permitidas.";
        } elseif ($arquivo['size'] > 2 * 1024 * 1024) {
            $erro = "A imagem deve ter no máximo 2MB.";
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $arquivo['tmp_name']);
            finfo_close($finfo);
            $mime_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($mime, $mime_permitidos)) {
                $erro = "Tipo de arquivo inválido.";
            } else {
                $nome_arquivo = uniqid('post_', true) . '.' . $ext;
                $caminho = 'uploads/posts/' . $nome_arquivo;
                if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                    // Remove imagem antiga se existir
                    if ($post['imagem'] && file_exists('uploads/posts/' . $post['imagem'])) {
                        unlink('uploads/posts/' . $post['imagem']);
                    }
                    $nova_imagem = $nome_arquivo;
                } else {
                    $erro = "Erro ao salvar a imagem.";
                }
            }
        }
    }

    if (empty($erro)) {
        $sql = "UPDATE posts SET titulo = ?, descricao = ?, imagem = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $descricao, $nova_imagem, $post_id]);
        $_SESSION['msg'] = "Post atualizado com sucesso!";
        header("Location: mainpage.php");
        exit;
    } else {
        // Se houve erro, armazena na sessão e volta para o formulário
        $_SESSION['erro_post'] = $erro;
        header("Location: atualizar_form.php?post_id=" . $post_id);
        exit;
    }
}

// Se for GET, exibe o formulário
$post_id = $_GET['post_id'] ?? 0;
if (!$post_id) {
    die("ID do post não informado.");
}

// Busca o post e verifica se é do usuário logado
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND usuario_id = ?");
$stmt->execute([$post_id, $usuario_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post não encontrado ou você não tem permissão.");
}

// Se houver mensagem de erro vinda da sessão, exibe
$erro = $_SESSION['erro_post'] ?? '';
unset($_SESSION['erro_post']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Post - Facefood</title>
    <link rel="stylesheet" href="CSS/mainstyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Inspiration&family=Roboto+Mono:wght@100..700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <a href="operações/logout.php"><img src="imgs/logout.png" alt="Sair" class="logout-icon"></a>
    <h1 style="font-family: 'Inspiration', cursive;" class="logo">Facefood.com</h1>
</header>
<section class="main-content-texture">
    <section class="feed">
        <h2>Editar Post</h2>
        <?php if ($erro): ?>
            <div style="color:red;"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form action="atualizar_form.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <label>Título:</label><br>
            <input type="text" name="titulo" value="<?= htmlspecialchars($post['titulo']) ?>" required><br>
            <label>Descrição:</label><br>
            <textarea name="descricao" rows="5" required><?= htmlspecialchars($post['descricao']) ?></textarea><br>
            <label>Imagem atual:</label><br>
            <img src="uploads/posts/<?= htmlspecialchars($post['imagem']) ?>" style="max-width:200px;"><br>
            <label>Nova imagem (opcional):</label><br>
            <input type="file" name="imagem" accept="image/*"><br><br>
            <button type="submit">Salvar alterações</button>
            <button type="button" onclick="window.location.href='mainpage.php'">Cancelar</button>
        </form>
    </section>
</section>
</body>
</html>