<?php
session_start();
require_once __DIR__ . '/../operações/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../operações/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Busca o post para garantir que é do usuário e pegar o nome da imagem
    $stmt = $pdo->prepare('SELECT imagem, usuario_id FROM posts WHERE id = :id');
    $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['usuario_id'] == $usuario_id) {
        // Deleta o post
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id AND usuario_id = :usuario_id');
        $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        // Remove a imagem do servidor
        if (!empty($post['imagem'])) {
            $caminho = __DIR__ . '/../uploads/posts/' . $post['imagem'];
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }
        
        $_SESSION['msg'] = 'Post deletado com sucesso!';
    } else {
        $_SESSION['msg'] = 'Você não tem permissão para deletar este post.';
    }
} else {
    $_SESSION['msg'] = 'Requisição inválida.';
}

header('Location: ../mainpage.php');
exit();
