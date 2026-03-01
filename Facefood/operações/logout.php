<?php
session_start();

// destruir a sessão para efetuar logout
session_destroy();

// limpar as variáveis de sessão
$_SESSION = [];

// Redirecionar para a página de login
header("Location: login.php");
exit();
?>