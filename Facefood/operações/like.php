<?php
session_start();
require "conexao.php";

// Define o tipo de resposta como JSON
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["success" => false]);
    exit();
}

// Verifica se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false]);
    exit();
}
$post_id = $_POST['post_id'];
$usuario_id = $_SESSION['usuario_id'];

// Verifica se já curtiu
$sql = "SELECT * FROM curtidas WHERE post_id = :post_id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":post_id", $post_id);
$stmt->bindParam(":usuario_id", $usuario_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Já curtiu → remover like
    $sql = "DELETE FROM curtidas WHERE post_id = :post_id AND usuario_id = :usuario_id";
    $liked = false;
} else {
    // Não curtiu → adicionar like
    $sql = "INSERT INTO curtidas (post_id, usuario_id) VALUES (:post_id, :usuario_id)";
    $liked = true;
}

// Executa a query de like/unlike
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":post_id", $post_id);
$stmt->bindParam(":usuario_id", $usuario_id);
$stmt->execute();

// Conta total atualizado
$sql = "SELECT COUNT(*) FROM curtidas WHERE post_id = :post_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":post_id", $post_id);
$stmt->execute();
$total_likes = $stmt->fetchColumn();

// Retorna resposta JSON
echo json_encode([
    "success" => true,
    "liked" => $liked,
    "total_likes" => $total_likes
]);