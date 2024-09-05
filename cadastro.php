<?php
session_start();
include_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $voluntario = isset($_POST['voluntario']) ? 1 : 0;
    $queradotar = isset($_POST['queradotar']) ? 1 : 0;
    $senha = $_POST['senha'];

    if (cadastrar_usuario($nome, $endereco, $cidade, $estado, $email, $telefone, $voluntario, $queradotar, $senha)) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form method="POST" action="">
        Nome: <input type="text" name="nome" required><br>
        Endereço: <input type="text" name="endereco" required><br>
        Cidade: <input type="text" name="cidade" required><br>
        Estado: <input type="text" name="estado" required><br>
        E-mail: <input type="email" name="email" required><br>
        Telefone: <input type="text" name="telefone" required><br>
        Voluntário: <input type="checkbox" name="voluntario"><br>
        Quer adotar: <input type="checkbox" name="queradotar"><br>
        Senha: <input type="password" name="senha" required><br>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>