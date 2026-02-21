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
    <section class="criar-post">
    <?php
    if (isset($_SESSION['erro_post'])) {
        echo '<div style="color:red;">' . $_SESSION['erro_post'] . '</div>';
        unset($_SESSION['erro_post']);
    }
    if (isset($_SESSION['msg'])) {
        echo '<div style="color:green;">' . $_SESSION['msg'] . '</div>';
        unset($_SESSION['msg']);
    }
    ?>
    <form action="operações/criar_post.php" method="POST" enctype="multipart/form-data" class="form-criar-post">
    
        <input type="file" name="imagem" accept="image/*" class="input-imagem" required>    

        <input type="text" name="titulo" placeholder="Título da receita" class="input-titulo" required>
        
        <textarea name="legenda" placeholder="Escreva a legenda..." class="input-legenda" required></textarea>
    
        
        <button type="submit">Publicar</button>
        
    </form>
    </section>

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

        foreach ($posts as $post) {
            echo "<div class='post'>";
            echo "<div class='post-header'>";
            echo "<img src='" . $post['foto_perfil'] . "' alt='Foto de perfil' class='foto-perfil'>";
            echo "<span>" . $post['nome'] . "</span>";
            // Botão deletar só para o dono do post
            if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $post['usuario_id']) {
                echo "<form method='POST' action='crud/deletar.php' style='display:inline;'>";
                echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";
                echo "<button type='submit' onclick=\"return confirm('Tem certeza que deseja deletar este post?');\" class='btn-deletar'>Deletar post</button>";
                echo "</form>";
            }
            // Botão editar post
            if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $post['usuario_id']) {
                echo "<form method='POST' action='atualizar_form.php' style='display:inline;'>";
                echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";
                echo "<button type='submit' onclick=\"return confirm('Tem certeza que deseja editar este post?');\" class='btn-editar'>Editar post</button>";
                echo "</form>";
            }
            echo "</div>";
            echo "<img src='uploads/posts/" . $post['imagem'] . "' alt='Imagem do post' class='imagem-post'>";
            echo "<h2>" . $post['titulo'] . "</h2>";
            echo "<p>" . $post['descricao'] . "</p>";

            echo "</div>";
        }
        ?>
    </section>
</section>
</body>
</html>