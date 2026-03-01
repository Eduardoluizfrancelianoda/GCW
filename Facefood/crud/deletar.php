<?php
session_start();
require_once "../operações/conexao.php"; // Ajuste o caminho conforme sua estrutura

// Verifica se o usuário está logado e se é o dono do post
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Primeiro, busca o post para verificar a propriedade e obter o nome da imagem
    $sql = "SELECT imagem, usuario_id FROM posts WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['usuario_id'] == $usuario_id) {
        // Armazena o nome da imagem antes de deletar o registro
        $imagem = $post['imagem'];

        // Deleta o registro do banco
        $sqlDelete = "DELETE FROM posts WHERE id = ?";
        $stmtDelete = $pdo->prepare($sqlDelete);
        if ($stmtDelete->execute([$post_id])) {
            // Se a exclusão no banco foi bem-sucedida, remove o arquivo de imagem
            if (!empty($imagem)) {
                $caminho_imagem = "../uploads/posts/" . $imagem; // Caminho relativo ao local deste arquivo
                if (file_exists($caminho_imagem)) {
                    unlink($caminho_imagem); // Deleta o arquivo
                }
            }
            $_SESSION['msg'] = "Post deletado com sucesso!";
        } else {
            $_SESSION['erro_post'] = "Erro ao deletar o post.";
        }
    } else {
        $_SESSION['erro_post'] = "Você não tem permissão para deletar este post.";
    }
}

header('Location: ../mainpage.php');
exit;
