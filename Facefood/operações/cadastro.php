<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Cadastro</title>
</head>
<body>
    <!-- Masthead-->
    <div>
        <div>
            <div>
                <h1>Crie sua Conta:</h1>
                <p>Preencha os campos abaixo para criar sua conta e acessar o sistema.</p>
                <form action="contas.php" method="POST" enctype="multipart/form-data">
                    <label>Foto de perfil:</label><br>
                        <input type="file" name="foto" accept="image/*" required>
                    <div> 
                        <input type="text" class="form-control" name="nome_usuario" placeholder="Digite o seu nome de usuário">
                    </div>   
                    <div> 
                        <input type="password" class="form-control" name="senha" placeholder="Digite a sua senha">
                    </div>
                    <button type="submit">Cadastrar</button>
                    <a href="intro.html" type="button">Voltar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>