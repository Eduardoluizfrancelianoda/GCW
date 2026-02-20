<?php
session_start();
require __DIR__ . '/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../operações/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../mainpage.php");
    exit();
}

$titulo = trim($_POST['titulo'] ?? '');
$legenda = trim($_POST['legenda'] ?? '');
$usuario_id = $_SESSION['usuario_id'];

if (empty($titulo) || empty($legenda)) {
    $_SESSION['erro_post'] = "Preencha todos os campos.";
    header("Location: mainpage.php");
    exit();
}

if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== 0) {
    $_SESSION['erro_post'] = "Erro no envio da imagem.";
    header("Location: mainpage.php");
    exit();
}

$imagem = $_FILES['imagem'];

$tipo = mime_content_type($imagem['tmp_name']);
if (!str_starts_with($tipo, "image/")) {
    $_SESSION['erro_post'] = "Arquivo inválido.";
    header("Location: mainpage.php");
    exit();
}

$nomeImagem = uniqid() . "_" . basename($imagem['name']);
$caminho = "uploads/posts/" . $nomeImagem;

if (!move_uploaded_file($imagem['tmp_name'], $caminho)) {
    $_SESSION['erro_post'] = "Erro ao salvar a imagem.";
    header("Location: mainpage.php");
    exit();
}


$sql = "INSERT INTO posts (usuario_id, titulo, descricao, imagem)
    VALUES (:usuario_id, :titulo, :descricao, :imagem)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->bindParam(':titulo', $titulo);
$stmt->bindParam(':descricao', $legenda);
$stmt->bindParam(':imagem', $nomeImagem);

$ok = false;
try {
    $ok = $stmt->execute();
} catch (PDOException $e) {
    $_SESSION['erro_post'] = 'Erro ao salvar no banco: ' . $e->getMessage();
    header("Location: mainpage.php");
    exit();
}
if ($ok) {
    header("Location: mainpage.php");
    exit();
} else {
    $_SESSION['erro_post'] = 'Erro desconhecido ao salvar no banco.';
    header("Location: mainpage.php");
    exit();
}
?>