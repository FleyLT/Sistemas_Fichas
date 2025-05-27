<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    header("Location: index.php");
    exit;
}

// Buscar as salas onde o usuário está aprovado
$sql = "SELECT s.*
        FROM salas s
        INNER JOIN salas_usuarios su ON su.sala_id = s.id
        WHERE su.usuario_id = $usuario_id AND su.aprovado = 1";

$result = mysqli_query($conn, $sql);

$salas = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $salas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Salas</title>
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
            color: white;
            background: #28a745;
            padding: 15px;
            border-radius: 10px;
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
        .status {
            padding: 4px 8px;
            border-radius: 5px;
            color: white;
            font-size: 12px;
        }
        .ativa { background: #28a745; }
        .encerrada { background: #dc3545; }

        .btn-voltar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            padding: 12px 18px;
            border-radius: 30px;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }
        .btn-voltar:hover {
            background: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📜 Minhas Salas</h1>

    <?php if (count($salas) > 0): ?>
        <table>
            <tr>
                <th>🏰 Nome da Sala</th>
                <th>🔑 Código</th>
                <th>📅 Criação</th>
                <th>🟢 Status</th>
            </tr>
            <?php foreach ($salas as $sala): ?>
                <tr>
                    <td><?= htmlspecialchars($sala['nome']) ?></td>
                    <td><?= htmlspecialchars($sala['codigo_acesso']) ?></td>
                    <td><?= date('d/m/Y', strtotime($sala['data_criacao'])) ?></td>
                    <td>
                        <span class="status <?= $sala['status'] ?>">
                            <?= ucfirst($sala['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>⚠️ Você não está participando de nenhuma sala no momento.</p>
    <?php endif; ?>
</div>

<a href="painel_jogador.php" class="btn-voltar">🏠 Voltar ao Painel</a>

</body>
</html>
