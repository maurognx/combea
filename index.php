<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Se o usuário estiver logado, redireciona para a dashboard
header("Location: dashboard.php");
exit();
?>
