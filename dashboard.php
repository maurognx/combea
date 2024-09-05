<?php
session_start();
include_once 'includes/functions.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obter a quantidade de animais cadastrados pelo proprietário
$cod_proprietario = $_SESSION['usuario']['codigo'];
$animais_proprietario = listar_animais_proprietario($cod_proprietario);
$quantidade_animais_proprietario = $animais_proprietario->num_rows;

// Obter a quantidade total de animais cadastrados no sistema
$animais_totais = listar_animais();
$quantidade_animais_totais = $animais_totais->num_rows;

// Saudação com base no período do dia
$hora_atual = date("H");
if ($hora_atual < 12) {
    $saudacao = "Bom dia";
} elseif ($hora_atual < 18) {
    $saudacao = "Boa tarde";
} else {
    $saudacao = "Boa noite";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center"><?php echo $saudacao; ?>, <?php echo $_SESSION['usuario']['nome']; ?>!</h2>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Animais Cadastrados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $quantidade_animais_proprietario; ?></h5>
                        <p class="card-text">Quantidade de animais cadastrados por você.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total de Animais</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $quantidade_animais_totais; ?></h5>
                        <p class="card-text">Quantidade total de animais cadastrados no sistema.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="d-flex flex-column">
                    <a href="cadastro_proprietario.php" class="btn btn-primary mb-2">Cadastrar Proprietário</a>
                    <a href="cadastro_animal.php" class="btn btn-primary mb-2">Cadastrar Animal</a>
                    <a href="cadastro_raca.php" class="btn btn-primary mb-2">Cadastrar Raça</a>
                    <a href="cadastro_porte.php" class="btn btn-primary mb-2">Cadastrar Porte</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-column">
                    <a href="cadastro_deficiencia.php" class="btn btn-primary mb-2">Cadastrar Deficiência</a>
                    <a href="cadastro_obito.php" class="btn btn-primary mb-2">Cadastrar Causa de Óbito</a>
                    <a href="consulta_proprietarios.php" class="btn btn-secondary mb-2">Consultar Proprietários</a>
                    <a href="consulta_animais.php" class="btn btn-secondary mb-2">Consultar Animais</a>
                    <a href="consulta_raca.php" class="btn btn-secondary mb-2">Consultar Raças</a>
                    <a href="consulta_deficiencia.php" class="btn btn-secondary mb-2">Consultar Deficiências</a>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="recuperar_senha.php" class="btn btn-secondary">Recuperar Senha</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
