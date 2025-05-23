<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$sala_id = $_GET['sala_id'] ?? null;

if (!$usuario_id || !$sala_id) {
    header("Location: entrar_salas.php");
    exit;
}

// Verificar se já está na sala
$check = mysqli_query($conn, "SELECT * FROM salas_usuarios WHERE sala_id = $sala_id AND usuario_id = $usuario_id");
if (mysqli_num_rows($check) == 0) {
    // Inserir na sala
    mysqli_query($conn, "INSERT INTO salas_usuarios (sala_id, usuario_id, aprovado) VALUES ($sala_id, $usuario_id, 0)");
}

header("Location: minhas_salas.php");
exit;
?>
