<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>🛒 Loja Geral</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .manutencao {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .manutencao h1 {
            margin-bottom: 10px;
            font-size: 28px;
        }
        .manutencao p {
            color: #555;
            margin-bottom: 20px;
        }
        .voltar {
            background: #007bff;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        .voltar:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="manutencao">
    <h1>🛠️ Loja em Manutenção</h1>
    <p>Estamos preparando seus itens incríveis para você! Volte em breve para conferir as novidades.</p>
    <p style="color: red; font-size: 12px;">⚠️ Obs: Por favor, coloque a sua sugestão para adicionarmos itens.</p>
    <a href="painel_jogador.php" class="voltar">🏠 Voltar ao Painel</a>
</div>

</body>
</html>
