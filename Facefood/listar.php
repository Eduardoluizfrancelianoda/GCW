<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "facefoodbd";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$sql = "SELECT id, nome, senha, foto_perfil FROM usuarios";
$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {
    echo "<h2>Lista de Usuários</h2>";

    while ($linha = $resultado->fetch_assoc()) {
        echo "<div style='margin-bottom:20px;'>";

        echo "<p><strong>ID:</strong> " . $linha["id"] . "</p>";
        echo "<p><strong>Nome:</strong> " . $linha["nome"] . "</p>";
        echo "<p><strong>senha:</strong> " . $linha["senha"] . "</p>";

        // Se tiver foto, mostrar
        if (!empty($linha["foto_perfil"])) {
            echo "<img src='" . $linha["foto_perfil"] . "' alt='Foto de Perfil' style='width:100px;height:100px;object-fit:cover;'><br>";
        } else {
            echo "<p>Sem foto de perfil</p>";
        }

        echo "<hr>";
        echo "</div>";
    }
} else {
    echo "Nenhum usuário encontrado.";
}

$conexao->close();
?>