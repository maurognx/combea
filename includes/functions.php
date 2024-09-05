<?php
include_once 'db.php'; // Inclui o arquivo de conexão com o banco de dados

function cadastrar_usuario($nome, $endereco, $cidade, $estado, $email, $telefone, $voluntario, $queradotar, $senha) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $senha_hash = md5($senha); // Armazenando a senha como MD5
    $sql = "INSERT INTO proprietarios (nome, endereco, cidade, estado, email, telefone, voluntario, queradotar, senha, nivel_acesso) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiss", $nome, $endereco, $cidade, $estado, $email, $telefone, $voluntario, $queradotar, $senha_hash);
    return $stmt->execute();
}

function login($email, $senha) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $senha_hash = md5($senha);
    $sql = "SELECT * FROM proprietarios WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function recuperar_senha($email) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM proprietarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario) {
        $senha_hash = $usuario['senha']; // Obtém a senha armazenada (MD5)

        // Envio de e-mail
        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gnx.com.br'; // Endereço do servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'notify@gnx.com.br'; // Usuário SMTP
            $mail->Password = 'notify2024!@'; // Senha SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita a criptografia TLS
            $mail->Port = 587; // Porta TCP para conexão

            // Destinatários
            $mail->setFrom('notify@gnx.com.br', 'COMBEA');
            $mail->addAddress($email); // Adiciona o destinatário

            // Conteúdo do e-mail
            $mail->isHTML(true); // Define o formato do e-mail como HTML
            $mail->Subject = 'COMBEA - Recuperação de senha';
            $mail->Body    = 'Sua senha é: ' . $senha_hash; // Envia a senha em MD5

            $mail->send();
            return true; // E-mail enviado com sucesso
        } catch (Exception $e) {
            return false; // Erro ao enviar o e-mail
        }
    }
    return false; // Usuário não encontrado
}

function cadastrar_animal($nome, $cod_raca, $cod_proprietario, $cod_porte, $peso, $vacinado, $falecido, $desaparecido, $dispadocao, $foto, $comunitario, $temdeficiencia, $idade, $datafalecimento, $sexo) {
    $conn = db_connect(); // Chama a função de conexão do db.php

    // Mover o arquivo enviado para uma pasta específica
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Cria o diretório se não existir
    }

    // Cria um nome único para o arquivo
    $extensao = pathinfo($foto, PATHINFO_EXTENSION); // Obtém a extensão do arquivo
    $nome_unico = uniqid() . '.' . $extensao; // Gera um ID único e mantém a extensão
    $target_file = $target_dir . $nome_unico;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO animais (nome, cod_raca, cod_proprietario, cod_porte, peso, vacinado, falecido, desaparecido, dispadocao, foto, comunitario, temdeficiencia, idade, datafalecimento, sexo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiidsiiissiiss", $nome, $cod_raca, $cod_proprietario, $cod_porte, $peso, $vacinado, $falecido, $desaparecido, $dispadocao, $target_file, $comunitario, $temdeficiencia, $idade, $datafalecimento, $sexo);
        
        if ($stmt->execute()) {
            $cod_animal = $conn->insert_id; // Obter o ID do animal cadastrado

            // Inserir deficiências selecionadas
            if ($temdeficiencia && !empty($_POST['cod_deficiencia'])) {
                foreach ($_POST['cod_deficiencia'] as $deficiencia) {
                    $sql_def = "INSERT INTO animal_deficiencia (cod_animal, cod_deficiencia) VALUES (?, ?)";
                    $stmt_def = $conn->prepare($sql_def);
                    $stmt_def->bind_param("ii", $cod_animal, $deficiencia);
                    $stmt_def->execute();
                }
            }

            // Inserir causas de óbito selecionadas
            if ($falecido && !empty($_POST['cod_causa_obito'])) {
                foreach ($_POST['cod_causa_obito'] as $causa) {
                    $sql_obito = "INSERT INTO animal_obito (cod_animal, cod_causa_obito) VALUES (?, ?)";
                    $stmt_obito = $conn->prepare($sql_obito);
                    $stmt_obito->bind_param("ii", $cod_animal, $causa);
                    $stmt_obito->execute();
                }
            }

            return true; // Cadastro bem-sucedido
        } else {
            return false; // Erro no cadastro
        }
    } else {
        return false; // Erro ao mover o arquivo
    }
}

function listar_animais() {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM animais";
    $result = $conn->query($sql);
    return $result;
}

function get_animal($codigo) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM animais WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function editar_animal($codigo, $nome, $cod_raca, $cod_proprietario, $cod_porte, $peso, $vacinado, $falecido, $desaparecido, $dispadocao, $foto, $comunitario, $temdeficiencia, $idade, $datafalecimento, $sexo) {
    $conn = db_connect(); // Chama a função de conexão do db.php

    // Se uma nova foto foi enviada, mover o arquivo
    if (!empty($foto)) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Cria o diretório se não existir
        }

        // Cria um nome único para o arquivo
        $extensao = pathinfo($foto, PATHINFO_EXTENSION); // Obtém a extensão do arquivo
        $nome_unico = uniqid() . '.' . $extensao; // Gera um ID único e mantém a extensão
        $target_file = $target_dir . $nome_unico;

        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            return false; // Erro ao mover o arquivo
        }
    } else {
        // Se não houver nova foto, manter a foto atual
        $sql = "SELECT foto FROM animais WHERE codigo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $animal = $result->fetch_assoc();
        $target_file = $animal['foto']; // Mantém a foto atual
    }

    $sql = "UPDATE animais SET nome = ?, cod_raca = ?, cod_proprietario = ?, cod_porte = ?, peso = ?, vacinado = ?, falecido = ?, desaparecido = ?, dispadocao = ?, foto = ?, comunitario = ?, temdeficiencia = ?, idade = ?, datafalecimento = ?, sexo = ? WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiidsiiissiissi", $nome, $cod_raca, $cod_proprietario, $cod_porte, $peso, $vacinado, $falecido, $desaparecido, $dispadocao, $target_file, $comunitario, $temdeficiencia, $idade, $datafalecimento, $sexo, $codigo);
    
    return $stmt->execute(); // Retorna true se a atualização for bem-sucedida
}

function get_raca($codigo) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM raca WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function get_porte($codigo) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM porte WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function get_proprietario($codigo) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM proprietarios WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function listar_animais_proprietario($cod_proprietario) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM animais WHERE cod_proprietario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cod_proprietario);
    $stmt->execute();
    return $stmt->get_result();
}

function get_deficiencias_animal($cod_animal) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT cod_deficiencia FROM animal_deficiencia WHERE cod_animal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cod_animal);
    $stmt->execute();
    $result = $stmt->get_result();
    $deficiencias = array();
    while ($row = $result->fetch_assoc()) {
        $deficiencias[] = $row['cod_deficiencia'];
    }
    return $deficiencias;
}

function get_causas_obito_animal($cod_animal) {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT cod_causa_obito FROM animal_obito WHERE cod_animal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cod_animal);
    $stmt->execute();
    $result = $stmt->get_result();
    $causas_obito = array();
    while ($row = $result->fetch_assoc()) {
        $causas_obito[] = $row['cod_causa_obito'];
    }
    return $causas_obito;
}

function update_deficiencias($cod_animal, $cod_deficiencia, $temdeficiencia) {
    $conn = db_connect(); // Chama a função de conexão do db.php

    // Primeiro, remove todas as deficiências existentes para o animal
    $sql_delete = "DELETE FROM animal_deficiencia WHERE cod_animal = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $cod_animal);
    $stmt_delete->execute();

    // Se o animal tiver deficiência, insere as novas deficiências
    if ($temdeficiencia && !empty($cod_deficiencia)) {
        foreach ($cod_deficiencia as $deficiencia) {
            $sql_insert = "INSERT INTO animal_deficiencia (cod_animal, cod_deficiencia) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ii", $cod_animal, $deficiencia);
            $stmt_insert->execute();
        }
    }
}

function update_causas_obito($cod_animal, $falecido, $cod_causa_obito) {
    $conn = db_connect(); // Chama a função de conexão do db.php

    // Primeiro, remove todas as causas de óbito existentes para o animal
    $sql_delete = "DELETE FROM animal_obito WHERE cod_animal = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $cod_animal);
    $stmt_delete->execute();

    // Se o animal estiver falecido, insere as novas causas de óbito
    if ($falecido && !empty($cod_causa_obito)) {
        foreach ($cod_causa_obito as $causa) {
            $sql_insert = "INSERT INTO animal_obito (cod_animal, cod_causa_obito) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ii", $cod_animal, $causa);
            $stmt_insert->execute();
        }
    }
}

function deletar_animal($codigo) {
    $conn = db_connect(); // Chama a função de conexão do db.php

    // Primeiro, remove as deficiências relacionadas ao animal
    $sql_delete_deficiencias = "DELETE FROM animal_deficiencia WHERE cod_animal = ?";
    $stmt_delete_deficiencias = $conn->prepare($sql_delete_deficiencias);
    $stmt_delete_deficiencias->bind_param("i", $codigo);
    $stmt_delete_deficiencias->execute();

    // Depois, remove as causas de óbito relacionadas ao animal
    $sql_delete_obitos = "DELETE FROM animal_obito WHERE cod_animal = ?";
    $stmt_delete_obitos = $conn->prepare($sql_delete_obitos);
    $stmt_delete_obitos->bind_param("i", $codigo);
    $stmt_delete_obitos->execute();

    // Finalmente, remove o animal da tabela animais
    $sql_delete_animal = "DELETE FROM animais WHERE codigo = ?";
    $stmt_delete_animal = $conn->prepare($sql_delete_animal);
    $stmt_delete_animal->bind_param("i", $codigo);
    return $stmt_delete_animal->execute();
}

function deletar_proprietario($codigo) {
    $conn = db_connect(); // Chama a função de conexão do db.php

    // Primeiro, obter todos os animais do proprietário
    $sql_animais = "SELECT codigo FROM animais WHERE cod_proprietario = ?";
    $stmt_animais = $conn->prepare($sql_animais);
    $stmt_animais->bind_param("i", $codigo);
    $stmt_animais->execute();
    $result_animais = $stmt_animais->get_result();

    // Excluir causas de óbito e deficiências para cada animal, depois excluir os animais
    while ($animal = $result_animais->fetch_assoc()) {
        $cod_animal = $animal['codigo'];

        // Excluir causas de óbito do animal
        $sql_delete_obitos = "DELETE FROM animal_obito WHERE cod_animal = ?";
        $stmt_delete_obitos = $conn->prepare($sql_delete_obitos);
        $stmt_delete_obitos->bind_param("i", $cod_animal);
        $stmt_delete_obitos->execute();

        // Excluir deficiências do animal
        $sql_delete_deficiencias = "DELETE FROM animal_deficiencia WHERE cod_animal = ?";
        $stmt_delete_deficiencias = $conn->prepare($sql_delete_deficiencias);
        $stmt_delete_deficiencias->bind_param("i", $cod_animal);
        $stmt_delete_deficiencias->execute();

        // Excluir o animal
        $sql_delete_animal = "DELETE FROM animais WHERE codigo = ?";
        $stmt_delete_animal = $conn->prepare($sql_delete_animal);
        $stmt_delete_animal->bind_param("i", $cod_animal);
        $stmt_delete_animal->execute();
    }

    // Finalmente, remover o proprietário
    $sql_delete_proprietario = "DELETE FROM proprietarios WHERE codigo = ?";
    $stmt_delete_proprietario = $conn->prepare($sql_delete_proprietario);
    $stmt_delete_proprietario->bind_param("i", $codigo);
    return $stmt_delete_proprietario->execute();
}

function listar_proprietarios() {
    $conn = db_connect(); // Chama a função de conexão do db.php
    $sql = "SELECT * FROM proprietarios";
    $result = $conn->query($sql);
    return $result;
}
?>