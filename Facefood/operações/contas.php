<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome_usuario'];
    $senha = $_POST['senha'];

    // Verifica se enviou imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

        $foto = $_FILES['foto'];

        $nomeArquivo = uniqid() . "_" . $foto['name'];

        $caminho = "uploads/fotos/" . $nomeArquivo;

        // move a imagem
        if (move_uploaded_file($foto['tmp_name'], $caminho)) {
            $foto_perfil = $caminho;
        } else {
            $foto_perfil = "uploads/fotos/default.jpg";
        }

    } else {
        $foto_perfil = "uploads/fotos/default.jpg";
    }

    $_SESSION['dados_cadastro'] = [
        'nome' => $nome,
        'senha' => $senha,
        'foto_perfil' => $foto_perfil
    ];

    header("Location: cadastro_final.php");
    exit;
}
?>