<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado
$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    header("Location: index.php");
    exit;
}

// Buscar as classes
$classes = [];
$res = mysqli_query($conn, "SELECT id, nome FROM classes");
while ($row = mysqli_fetch_assoc($res)) {
    $classes[] = $row;
}

// Criar ficha
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_personagem = mysqli_real_escape_string($conn, $_POST['nome_personagem']);
    $classe_id = (int)$_POST['classe_id'];

    // Criar a ficha
    $sql = "INSERT INTO fichas (usuario_id, classe_id, nome_personagem)
            VALUES ($usuario_id, $classe_id, '$nome_personagem')";
    if (mysqli_query($conn, $sql)) {
        $ficha_id = mysqli_insert_id($conn);

        // Atributos padrão
        $atributos = ['forca', 'destreza', 'inteligencia', 'constituicao', 'sabedoria', 'carisma'];
        foreach ($atributos as $atributo) {
            $insert_atributo = "INSERT INTO ficha_atributos (ficha_id, atributo, valor)
                                VALUES ($ficha_id, '$atributo', 10)";
            mysqli_query($conn, $insert_atributo);
        }

        echo "<p>✅ Ficha criada com sucesso!</p>";
    } else {
        echo "<p>❌ Erro ao criar ficha.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Ficha - Sistema Ficha</title>
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

    h2 {
        text-align: center;
        color: #212529;
        margin-bottom: 24px;
    }

    .form-container {
        background-color: white;
        padding: 30px;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        margin: auto;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }

    .form-container:hover {
        transform: scale(1.01);
    }

    label {
        font-weight: 600;
        color: #495057;
        display: block;
        margin-bottom: 6px;
    }

    input[type="text"], select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 10px;
        background-color: #f8f9fa;
        color: #495057;
        margin-bottom: 16px;
        transition: border-color 0.3s, background-color 0.3s;
    }

    input[type="text"]:focus, select:focus {
        border-color: #0d6efd;
        background-color: #fff;
        outline: none;
    }

    input[type="submit"] {
        background-color: #198754;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        width: 100%;
        transition: background-color 0.3s, transform 0.2s;
    }

    input[type="submit"]:hover {
        background-color: #157347;
        transform: scale(1.03);
    }

    .btn-voltar {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #0d6efd;
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
        background-color: #0a58ca;
        transform: scale(1.07);
    }

    p {
        text-align: center;
        color: #212529;
        background-color: #d1e7dd;
        padding: 10px;
        border-radius: 8px;
        margin-top: 20px;
    }

    p.error {
        background-color: #f8d7da;
    }

    @media (max-width: 600px) {
        .form-container {
            padding: 20px;
        }

        .btn-voltar {
            padding: 10px 16px;
        }
    }
</style>

    </style>
</head>
<body>

<h2 style="text-align:center;">🧙 Criar Nova Ficha</h2>

<div class="form-container">
    <form method="POST">
        <label>Nome do Personagem:</label>
        <input type="text" name="nome_personagem" required>

        <label>Classe:</label>
        <select name="classe_id" required>
            <option value="">Selecione</option>
            <?php foreach ($classes as $classe): ?>
                <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nome']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Criar Ficha">
    </form>
         
</div>
                <a href="painel_jogador.php" class="btn-voltar">🏠 Voltar ao Painel</a>
</body>
</html>