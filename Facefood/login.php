<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Início</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <div>
            <div>
                <div>
                    <h1>Faça seu Login aqui:</h1>
                    <!--  Exibe mensagem de erro, se existir -->
                    <?php if (isset($_SESSION['erro_login'])): ?>
                    <div class="alert alert-danger" role="alert" style="max-width: 400px;">
                        <?php 
                            echo $_SESSION['erro_login']; 
                            unset($_SESSION['erro_login']); // limpa pra não repetir
                        ?>
                    </div>
                    <?php endif; ?>
                    <!-- Usuário põe as informações da conta, e é checado em checar.php -->
                    <form action="checar.php" method="POST">
                        
                        <div> 
                            <input type="text" class="form-control" name="nome_usuario" placeholder="Digite o seu nome de usuário" required>
                        </div>
                        <div> 
                            <input type="password" class="form-control" name="senha" placeholder="Digite a sua senha" required>
                        </div>
                        <div>
                        </div>
                        <button type="submit">Entrar</button>

                        <a href="form_cadastra.php" type="button">Criar Conta</a>

                    </form>
                </div>
            </div>
        </div>
       
    </body>
</html>