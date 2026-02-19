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
    <h1 style="font-family: 'Inspiration', cursive;" class="logo">Facefood.com</h1>
</header>
<body>
    <section class="criar-post">
    <form action="criar_post.php" method="POST" enctype="multipart/form-data">
        
        <input type="text" name="titulo" placeholder="Título da receita" required>
        
        <textarea name="legenda" placeholder="Escreva a legenda..." required></textarea>
        
        <input type="file" name="imagem" accept="image/*" required>
        
        <button type="submit">Publicar</button>
        
    </form>
    </section>

    <section class="feed">
        <?php
        session_start();
        require "conexao.php";

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
                echo "<img src='uploads/fotos/" . $post['foto_perfil'] . "' alt='Foto de perfil' class='foto-perfil'>";
            echo "<span>" . $post['nome'] . "</span>";
            echo "</div>";
            echo "<h2>" . $post['titulo'] . "</h2>";
                echo "<p>" . $post['descricao'] . "</p>";
                echo "<img src='uploads/posts/" . $post['imagem'] . "' alt='Imagem do post' class='imagem-post'>";
            echo "</div>";
        }
        ?>
    </section>
</body>
</html>