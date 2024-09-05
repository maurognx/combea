<?php
session_start();
include_once 'includes/functions.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $cod_proprietario = $_GET['delete'];
    if (deletar_proprietario($cod_proprietario)) {
        header("Location: consulta_proprietarios.php?success=deleted");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erro ao deletar proprietário.</div>";
    }
}

$proprietarios = listar_proprietarios();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Proprietários</title>
</head>
<body>
    <h2>Consulta de Proprietários</h2>
    <?php if (isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
        <div class="alert alert-success">Proprietário deletado com sucesso!</div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($proprietario = $proprietarios->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $proprietario['nome']; ?></td>
                    <td><?php echo $proprietario['email']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $proprietario['codigo']; ?>" onclick="return confirm('Tem certeza que deseja deletar este proprietário?');">Deletar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="dashboard.php">Voltar</a>
</body>
</html>