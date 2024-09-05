<?php
include_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (recuperar_senha($email)) {
        echo "E-mail de recuperação enviado com sucesso!";
    } else {
        echo "Erro ao enviar e-mail. Verifique se o e-mail está cadastrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
</head>
<body>
    <h2>Recuperação de Senha</h2>
    <form method="POST" action="">
        E-mail: <input type="email" name="email" required><br>
        <input type="submit" value="Recuperar Senha">
    </form>
</body>
</html>