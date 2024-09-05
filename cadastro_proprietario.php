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
        echo "<div class='alert alert-success'>Proprietário cadastrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar proprietário.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Proprietário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastro de Proprietário</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" class="form-control" name="endereco" required>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" class="form-control" name="cidade" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" class="form-control" name="estado" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" class="form-control" name="telefone" required>
            </div>
            <div class="form-group">
                <label>Voluntário:</label>
                <input type="checkbox" name="voluntario">
            </div>
            <div class="form-group">
                <label>Quer adotar:</label>
                <input type="checkbox" name="queradotar">
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
