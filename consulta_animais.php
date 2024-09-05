<?php
session_start();
include_once 'includes/functions.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $cod_animal = $_GET['delete'];
    if (deletar_animal($cod_animal)) {
        header("Location: consulta_animais.php?success=deleted");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erro ao deletar animal.</div>";
    }
}

$cod_proprietario = $_SESSION['usuario']['codigo'];
$animais = listar_animais_proprietario($cod_proprietario);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Animais</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Consulta de Animais</h2>
        <?php if (isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
            <div class="alert alert-success">Animal deletado com sucesso!</div>
        <?php endif; ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Raça</th>
                    <th>Porte</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($animal = $animais->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $animal['nome']; ?></td>
                        <td><?php echo get_raca($animal['cod_raca'])['nome']; ?></td>
                        <td><?php echo get_porte($animal['cod_porte'])['nome']; ?></td>
                        <td>
                            <a href="editar_animal.php?codigo=<?php echo $animal['codigo']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?delete=<?php echo $animal['codigo']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este animal?');">Deletar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
