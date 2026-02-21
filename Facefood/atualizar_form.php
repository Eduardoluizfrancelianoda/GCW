<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>facefood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
    href="https://fonts.googleapis.com/css2?family=Inspiration&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="CSS/mainstyle.css">
</head>
<header>
    <a href="operações/logout.php"><img src="imgs/logout.png" alt="Sair" class="logout-icon"></a>
    <h1 style="font-family: 'Inspiration', cursive;" class="logo">Facefood.com</h1>
</header>
<body>
    <section class="main-content-texture">

    <section class="feed">
        <?php
        session_start();
        require "operações/conexao.php";
        $sql = "SELECT posts.*, usuarios.nome, usuarios.foto_perfil 
                FROM posts 
                JOIN usuarios ON posts.usuario_id = usuarios.id
                ORDER BY posts.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Se recebeu post_id via POST ou GET, mostra formulário de edição
        if (isset($_POST['post_id'])) {
            $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : $_GET['post_id'];
            // Busca post específico
            $sql = "SELECT * FROM posts WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$post_id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($post) {
                // Se enviou atualização
                if (isset($_POST['titulo']) && isset($_POST['descricao'])) {
                    $novo_titulo = $_POST['titulo'];
                    $nova_descricao = $_POST['descricao'];
                    $nova_imagem = $post['imagem']; // valor padrão
                    // Processa upload de imagem
                    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
                        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                        $nome_arquivo = uniqid('post_', true) . '.' . $ext;
                        $caminho = 'uploads/posts/' . $nome_arquivo;
                        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
                            $nova_imagem = $nome_arquivo;
                        }
                    }
                    $sql = "UPDATE posts SET titulo = ?, descricao = ?, imagem = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$novo_titulo, $nova_descricao, $nova_imagem, $post_id]);
                    echo "<p>Post atualizado com sucesso!</p>";
                    // Atualiza post para exibir nova imagem
                    $post['titulo'] = $novo_titulo;
                    $post['descricao'] = $nova_descricao;
                    $post['imagem'] = $nova_imagem;
                }
                // Formulário de edição
                echo "<form action='atualizar_form.php' method='post' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";
                echo "<label>Título:</label><br>";
                echo "<input type='text' name='titulo' value='" . htmlspecialchars($post['titulo']) . "'><br>";
                echo "<label>Descrição:</label><br>";
                echo "<textarea name='descricao' style='width:100%; height:100px;'>" . htmlspecialchars($post['descricao']) . "</textarea><br>";
                echo "<label>Imagem atual:</label><br>";
                echo "<img src='uploads/posts/" . $post['imagem'] . "' alt='Imagem do post' style='max-width:200px;'><br>";
                echo "<label>Nova imagem:</label><br>";
                echo "<input type='file' name='imagem'><br>";
                echo "<button type='submit'>Salvar</button>";
                echo "<button type='button' onclick=\"window.location.href='mainpage.php';\">ir para a pagina principal</button>";
                echo "</form>";
            } else {
                echo "<p>Post não encontrado.</p>";
            }
        } else {
            // Lista posts com botão de atualizar
            foreach ($posts as $post) {
                echo "<div class='post'>";
                echo "<div class='post-header'>";
                echo "<img src='" . $post['foto_perfil'] . "' alt='Foto de perfil' class='foto-perfil'>";
                echo "<span>" . $post['nome'] . "</span>";
                echo "</div>";
                echo "<img src='uploads/posts/" . $post['imagem'] . "' alt='Imagem do post' class='imagem-post'>";
                echo "<h2>" . $post['titulo'] . "</h2>";
                echo "<p>" . $post['descricao'] . "</p>";
                echo "<form method='get' style='margin-top:10px;'>";
                echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";
                echo "<button type='submit'>Atualizar</button>";
                echo "</form>";
                echo "</div>";
            }
        }
        ?>
    </section>
</section>
</body>
</html>