<?php
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Painel do Jogador</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .painel { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        .card {
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;
        }
        .card a { text-decoration: none; color: #333; font-weight: bold; display: block; margin-top: 10px; }
        .titulo { text-align: center; font-size: 24px; margin-bottom: 30px; }
    </style>
</head>
<body>

<h1 class="titulo">🎮 Painel do Jogador</h1>

<div class="painel">

    <div class="card">
        <div>📥 Entrar em Salas</div>
        <a href="entrar_salas.php">Ver Salas Disponíveis</a>
    </div>

    <div class="card">
        <div>📋 Minhas Salas</div>
        <a href="minhas_salas.php">Salas que Estou Participando</a>
    </div>

    <div class="card">
        <div>🧙‍♂️ Criar Ficha</div>
        <a href="criar_ficha.php">Nova Ficha</a>
    </div>

    <div class="card">
        <div>📈 Evoluir Ficha</div>
        <a href="evoluir_ficha.php">Usar XP</a>
    </div>

    <div class="card">
        <div>🎒 Inventário</div>
        <a href="inventario.php">Ver Itens</a>
    </div>

    <div class="card">
        <div>🛒 Shop Geral</div>
        <a href="shop.php">Comprar Itens</a>
    </div>

    <div class="card">
        <div>💡 Sugerir Conteúdo</div>
        <a href="sugerir.php">Habilidades e Itens</a>
    </div>

</div>

</body>
</html>
