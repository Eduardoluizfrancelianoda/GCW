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
        <link href="../css/introstyle.css" rel="stylesheet" />
    </head>
    <body>
           <!-- Masthead-->
    <div>
        <div class="main-content-texture">
            <div class="main-content">
                <div class="left-column">
                <img src="../imgs/milharal.PNG" alt="Milharal" class="bg-image">
                <img src="../gifs/vaca olhano pra cima.gif" alt="Vaca no milharal" class="cow">
                <img src="../gifs/vaca de boa.gif" alt="Vaca no milharal de boa" class="cow-2">
                <img src="../gifs/ufo.gif" alt="ufo massa" class="ufo">
            </div>

            <div class="right-column">
                <div class="bg-image" style="background-image: url(../imgs/restaurante\ massa.jpg);">
                    <img src="../gifs/prato de fritas.gif" alt="fritas" class="fries">
                </div>
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
                    <a href="../intro.html" type="button">Voltar</a>
                </form>
            </div>
        </div>
    </div>
       
    </body>
</html>