<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar em Salas</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            margin: 0; 
            padding: 20px;
        }
        .container { 
            max-width: 1000px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 { 
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            background: #007bff;
            padding: 15px;
            border-radius: 10px;
            color: white;
        }
        table {
            width: 100%; 
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center;
        }
        th {
            background-color: #343a40; 
            color: white;
        }
        tr:nth-child(even) {background-color: #f9f9f9;}
        tr:hover {background-color: #f1f1f1;}
        .btn {
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #218838;
        }
        .btn-voltar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            transition: background 0.3s;
        }
        .btn-voltar:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🏰 Salas Disponíveis</h1>

    <div id="tabela-salas">
        <!-- A tabela será carregada aqui -->
    </div>
</div>

<a href="painel_jogador.php" class="btn-voltar">🏠 Voltar ao Painel</a>>

<script>
// Função para buscar as salas via AJAX
function carregarSalas() {
    fetch('listar_salas.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('tabela-salas').innerHTML = data;
        });
}

// Atualiza a cada 10 segundos
setInterval(carregarSalas, 10000);

// Carrega ao abrir a página
carregarSalas();
</script>

</body>
</html>
