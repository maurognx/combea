<?php
function db_connect() {
    $servername = "localhost";
    $username = "mauro_combea"; // Altere conforme necessário
    $password = "combea2024!@"; // Altere conforme necessário
    $dbname = "mauro_combea"; // Altere conforme necessário

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
