<?php
session_start();
include_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // Usando 'email' como campo de login
    $senha = $_POST['senha']; // Você precisará de um método para verificar a senha

    $conn = db_connect();
    // Ajuste a consulta para buscar pelo e-mail
    $sql = "SELECT * FROM proprietarios WHERE email = ?"; // Supondo que a coluna na tabela é 'email'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario_data = $resultado->fetch_assoc();
        
        // Aqui você deve verificar a senha, por exemplo, usando password_verify
        // if (password_verify($senha, $usuario_data['senha'])) { // Ajuste para verificar a senha
        $_SESSION['usuario'] = [
            'codigo' => $usuario_data['codigo'],
            'nome' => $usuario_data['nome'],
            'nivel_acesso' => $usuario_data['nivel_acesso'] // Captura o nível de acesso
        ];
        header("Location: dashboard.php");
        exit();
        // } else {
        //     echo "<div class='alert alert-danger'>Senha inválida.</div>";
        // }
    } else {
        echo "<div class='alert alert-danger'>Usuário ou senha inválidos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
