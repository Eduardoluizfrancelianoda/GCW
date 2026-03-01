<?php
session_start();
require_once "operações/conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Busca o post para verificar propriedade
$sql = "SELECT * FROM posts WHERE id = ? AND usuario_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id, $usuario_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    $_SESSION['erro_post'] = "Post não encontrado ou você não tem permissão.";
    header('Location: mainpage.php');
    exit;
}

// Processa atualização se formulário enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $imagem_atual = $post['imagem'];

    // Validações básicas
    if (empty($titulo) || empty($descricao)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Processa upload de nova imagem, se houver
        $nova_imagem = $imagem_atual; // mantém a atual por padrão
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            // Validações de tipo e tamanho (exemplo básico)
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($ext, $ext_permitidas)) {
                $erro = "Formato de imagem não permitido.";
            } elseif ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
                $erro = "A imagem deve ter no máximo 2MB.";
            } else {
                $nome_arquivo = uniqid('post_', true) . '.' . $ext;
                $caminho = 'uploads/posts/' . $nome_arquivo;
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
                    $nova_imagem = $nome_arquivo;
                    // Remove imagem antiga se existir
                    if (!empty($imagem_atual) && file_exists('uploads/posts/' . $imagem_atual)) {
                        unlink('uploads/posts/' . $imagem_atual);
                    }
                } else {
                    $erro = "Erro ao fazer upload da imagem.";
                }
            }
        }

        if (!isset($erro)) {
            $sqlUpdate = "UPDATE posts SET titulo = ?, descricao = ?, imagem = ? WHERE id = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            if ($stmtUpdate->execute([$titulo, $descricao, $nova_imagem, $post_id])) {
                $_SESSION['msg'] = "Post atualizado com sucesso!";
                header('Location: mainpage.php');
                exit;
            } else {
                $erro = "Erro ao atualizar o post.";
            }
        }
    }
}
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