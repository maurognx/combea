<?php
session_start();
include_once 'includes/functions.php';

$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cod_raca = $_POST['cod_raca'];
    $cod_proprietario = $_SESSION['usuario']['codigo'];
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

    $sql_animal = "INSERT INTO animais (nome, cod_raca, cod_proprietario, cod_porte, peso, vacinado, falecido, desaparecido, dispadocao, foto, comunitario, temdeficiencia, idade, datafalecimento, sexo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_animal = $conn->prepare($sql_animal);
    $stmt_animal->bind_param("siidiiiiiississ", $nome, $cod_raca, $cod_proprietario, $cod_porte, $peso, $vacinado, $falecido, $desaparecido, $dispadocao, $foto, $comunitario, $temdeficiencia, $idade, $datafalecimento, $sexo);
    
    if ($stmt_animal->execute()) {
        $cod_animal = $conn->insert_id;

        if ($temdeficiencia && !empty($_POST['cod_deficiencia'])) {
            foreach ($_POST['cod_deficiencia'] as $deficiencia) {
                $sql_def = "INSERT INTO animal_deficiencia (cod_animal, cod_deficiencia) VALUES (?, ?)";
                $stmt_def = $conn->prepare($sql_def);
                $stmt_def->bind_param("ii", $cod_animal, $deficiencia);
                $stmt_def->execute();
            }
        }

        if ($falecido && !empty($_POST['cod_causa_obito'])) {
            foreach ($_POST['cod_causa_obito'] as $causa) {
                $sql_obito = "INSERT INTO animal_obito (cod_animal, cod_causa_obito) VALUES (?, ?)";
                $stmt_obito = $conn->prepare($sql_obito);
                $stmt_obito->bind_param("ii", $cod_animal, $causa);
                $stmt_obito->execute();
            }
        }

        echo "<div class='alert alert-success'>Animal cadastrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar animal.</div>";
    }
}

// Obter raças, portes, deficiências e causas de óbito do banco de dados
$sql_racas = "SELECT * FROM raca";
$sql_portes = "SELECT * FROM porte";
$racas = $conn->query($sql_racas);
$portes = $conn->query($sql_portes);
$deficiencias = $conn->query("SELECT * FROM deficiencias");
$causas_obito = $conn->query("SELECT * FROM causas_obito");
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
                    <option value="">Selecione uma raça</option>
                    <?php while ($raca = $racas->fetch_assoc()): ?>
                        <option value="<?php echo $raca['codigo']; ?>"><?php echo $raca['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cod_porte">Porte:</label>
                <select class="form-control" name="cod_porte" required>
                    <option value="">Selecione um porte</option>
                    <?php while ($porte = $portes->fetch_assoc()): ?>
                        <option value="<?php echo $porte['codigo']; ?>"><?php echo $porte['nome']; ?></option>
                    <?php endwhile; ?>
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
                <input type="checkbox" name="falecido" id="falecido" onclick="toggleObitoSelection()">
            </div>
            <div class="form-group" id="obitoSelection" style="display:none;">
                <label>Motivos de Óbito:</label>
                <select class="form-control" name="cod_causa_obito[]" multiple>
                    <?php while ($causa = $causas_obito->fetch_assoc()): ?>
                        <option value="<?php echo $causa['codigo']; ?>"><?php echo $causa['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
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
                <input type="checkbox" name="temdeficiencia" id="temdeficiencia" onclick="toggleDeficienciaSelection()">
            </div>
            <div class="form-group" id="deficienciaSelection" style="display:none;">
                <label>Deficiências:</label>
                <select class="form-control" name="cod_deficiencia[]" multiple>
                    <?php while ($deficiencia = $deficiencias->fetch_assoc()): ?>
                        <option value="<?php echo $deficiencia['codigo']; ?>"><?php echo $deficiencia['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
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
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleDeficienciaSelection() {
            const checkbox = document.getElementById('temdeficiencia');
            const selection = document.getElementById('deficienciaSelection');
            selection.style.display = checkbox.checked ? 'block' : 'none';
        }

        function toggleObitoSelection() {
            const checkbox = document.getElementById('falecido');
            const selection = document.getElementById('obitoSelection');
            selection.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
</body>
</html>
