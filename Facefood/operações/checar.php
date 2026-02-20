<?php

    session_start();    
    include_once __DIR__ . '/conexao.php';
    
    $nome = $_POST['nome_usuario'];
    $senha = $_POST['senha'];
    
    $consulta = "SELECT * FROM usuarios WHERE nome = :nome_usuario";
    
    $stmt = $pdo->prepare($consulta);
    
    // Vincula os parâmetros
    $stmt->bindParam(':nome_usuario', $nome);
    
    // Executa a consulta
    $stmt->execute();
    
    // Obtém o resultado
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if($resultado){

    // Verifica a senha usando password_verify
    if(password_verify($senha, $resultado['senha'])){

        $_SESSION['nome'] = $resultado['Nome'];
        $_SESSION['usuario_id'] = $resultado['id'];
        $_SESSION['foto_perfil'] = $resultado['foto_perfil'];

        header('Location: ../mainpage.php');
        exit;

    } else {

        $_SESSION['erro_login'] = "Senha incorreta!";
        header('Location: login.php');
        exit;

    }

} else {

    $_SESSION['erro_login'] = "Usuário não encontrado!";
    header('Location: login.php');
    exit;
}
?>