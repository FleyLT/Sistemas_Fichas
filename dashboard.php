<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar cargo do usuário no banco de dados
$query = "SELECT papel, nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$usuario_nome = $user['nome'];
$papel = $user['papel'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Ficha</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <div class="welcome-message">
        Bem-vindo, <?php echo htmlspecialchars($usuario_nome); ?>!
    </div>

    <div class="cards-container">
        <!-- Painel Jogador -->
        <div class="card card-jogador">
            <h3>Painel Jogador</h3>
            <button onclick="window.location.href='painel_jogador.php'" style="color:rgb(3, 3, 3);">Ir para o Painel</button>
        </div>

        <!-- Painel Mestre -->
        <div class="card card-mestre">
            <h3>Painel Mestre</h3>
            <button onclick="window.location.href='painel_mestre.php'" style="color:rgb(3, 3, 3);">Ir para o Painel</button>
        </div>

        <!-- Painel Admin -->
        <div class="card card-admin">
            <h3>Painel Admin</h3>
            <button onclick="window.location.href='painel_admin.php'" style="color:rgb(3, 3, 3);">Ir para o Painel</button>
        </div>
    </div>

    <!-- Botão Logout -->
    <a href="logout.php" class="logout-button">Sair</a>

</body>
</html>
