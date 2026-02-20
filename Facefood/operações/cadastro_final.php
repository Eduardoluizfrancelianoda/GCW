<?php
    session_start();
    require __DIR__ . '/conexao.php';

    // Verifica se os dados foram enviados via sessão
    if (!isset($_SESSION['dados_cadastro'])) {
        die("Nenhum dado recebido.");
    }

    // Recupera os dados da sessão
    $dados = $_SESSION['dados_cadastro'];

    // Extrai os dados

    $nome = $dados['nome'];
    $senha = password_hash($dados['senha'], PASSWORD_DEFAULT);
    $foto_perfil = $dados['foto_perfil'];

    // Conexão com o banco e o INSERT
    $con = mysqli_connect($host, $user, $pass, $dbname);
    if(!$con){
        die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Insere os dados na tabela usuario
    $sql = "INSERT INTO usuarios (Nome, senha, foto_perfil) VALUES ('$nome', '$senha', '$foto_perfil')";

    // Executa a query e verifica se foi bem-sucedida
    $rs = mysqli_query($con, $sql);
    if($rs){
        // Busca o id do usuário recém cadastrado
        $usuario_id = mysqli_insert_id($con);
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['nome'] = $nome;
        $_SESSION['foto_perfil'] = $foto_perfil;

        // Limpa os dados de cadastro da sessão
        unset($_SESSION['dados_cadastro']);

        // Redireciona para a área logada
        header("Location: ../mainpage.php");
        exit;
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($con);
    }
?>