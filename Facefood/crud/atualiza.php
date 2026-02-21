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
            }
?>