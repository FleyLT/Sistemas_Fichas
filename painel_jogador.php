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
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f1f3f5;
        margin: 0;
        padding: 20px;
    }

    h1.titulo {
        text-align: center;
        font-size: 32px;
        color: #212529;
        margin-bottom: 40px;
    }

    .painel {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: auto;
    }

    .card {
        background-color: white;
        padding: 30px 20px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

    .card div {
        font-size: 20px;
        color: #343a40;
        margin-bottom: 12px;
    }

    .card a {
        display: inline-block;
        text-decoration: none;
        color: #0d6efd;
        background-color: #e7f1ff;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }

    .card a:hover {
        background-color: #0d6efd;
        color: white;
    }

    .btn-voltar {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #0056b3;
        color: white;
        padding: 14px 20px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        font-weight: bold;
        transition: background-color 0.3s, transform 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-voltar:hover {
        background-color: #007bff;
        transform: scale(1.07);
    }

    @media (max-width: 600px) {
        h1.titulo {
            font-size: 26px;
        }

        .card div {
            font-size: 18px;
        }

        .btn-voltar {
            padding: 10px 16px;
        }
    }
</style>

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

<a href="index.php" class="btn-voltar">🏠 Logout</a>

</body>
</html>
