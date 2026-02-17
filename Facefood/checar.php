<?php
    session_start();    
    include_once 'conexao.php';
    
    $nome = $_POST['nome_usuario'];
    $senha = $_POST['senha'];
    
    $consulta = "SELECT * FROM usuario WHERE Nome = :nome_usuario AND Senha = :senha";
    
    $stmt = $pdo->prepare($consulta);
    
    // Vincula os parâmetros
    $stmt->bindParam(':nome_usuario', $nome);
    $stmt->bindParam(':senha', $senha);
    
    // Executa a consulta
    $stmt->execute();

    // Obtém o número de registros encontrados
    $registros = $stmt->rowCount();
    
    // Obtém o resultado
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


    
    if($registros == 1){
        $_SESSION['nome'] = $resultado['Nome'];
        header('Location: mainpage.php');
        exit;

     }else{      
        $_SESSION['erro_login'] = "Login inválido! Verifique suas informações e tente novamente.";
        header('Location: login.php');
        exit;
    }
?>