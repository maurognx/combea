<?php
session_start();
include_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cod_raca = $_POST['cod_raca'];
    $cod_proprietario = $_SESSION['usuario']['codigo']; // Assume que o usuário está logado
    $cod_porte = $_POST['cod_porte'];
    $peso = $_POST['peso'];
    $vacinado = isset($_POST['vacinado']) ? 1 : 0;
    $falecido = isset($_POST['falecido']) ? 1 : 0;
    $desaparecido = isset($_POST['desaparecido']) ? 1 : 0;
    $dispadocao = isset($_POST['dispadocao']) ? 1 : 0;
    $foto = $_FILES['foto']['name'];
    $comunitario = isset($_POST['comunitario']) ? 1 : 0;
    $temdeficiencia = isset($_POST['temdeficiencia']) ? 1 : 0;
    $idade = $_POST['idade'];
    $datafalecimento = $_POST['datafalecimento'];
    $sexo = $_POST['sexo'];

    if (cadastrar_animal($nome, $cod_raca, $cod_proprietario, $cod_porte, $peso, $vacinado, $falecido, $desaparecido, $dispadocao, $foto, $comunitario, $temdeficiencia, $idade, $datafalecimento, $sexo)) {
        echo "<div class='alert alert-success'>Animal cadastrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar animal.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Animal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastro de Animal</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label for="cod_raca">Raça:</label>
                <select class="form-control" name="cod_raca" required>
                    <!-- Aqui você deve preencher as opções com as raças disponíveis -->
                    <option value="1">Raça 1</option>
                    <option value="2">Raça 2</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cod_porte">Porte:</label>
                <select class="form-control" name="cod_porte" required>
                    <!-- Aqui você deve preencher as opções com os portes disponíveis -->
                    <option value="1">Porte 1</option>
                    <option value="2">Porte 2</option>
                </select>
            </div>
            <div class="form-group">
                <label for="peso">Peso:</label>
                <input type="number" class="form-control" name="peso" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Vacinado:</label>
                <input type="checkbox" name="vacinado">
            </div>
            <div class="form-group">
                <label>Falecido:</label>
                <input type="checkbox" name="falecido">
            </div>
            <div class="form-group">
                <label>Desaparecido:</label>
                <input type="checkbox" name="desaparecido">
            </div>
            <div class="form-group">
                <label>Dispadocao:</label>
                <input type="checkbox" name="dispadocao">
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" class="form-control" name="foto" required>
            </div>
            <div class="form-group">
                <label>Comunitário:</label>
                <input type="checkbox" name="comunitario">
            </div>
            <div class="form-group">
                <label>Tem Deficiência:</label>
                <input type="checkbox" name="temdeficiencia">
            </div>
            <div class="form-group">
                <label for="idade">Idade:</label>
                <input type="number" class="form-control" name="idade" required>
            </div>
            <div class="form-group">
                <label for="datafalecimento">Data de Falecimento:</label>
                <input type="date" class="form-control" name="datafalecimento">
            </div>
            <div class="form-group">
                <label for="sexo">Sexo:</label>
                <select class="form-control" name="sexo" required>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
