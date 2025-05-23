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
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .form-container { background: white; padding: 20px; border-radius: 10px; width: 400px; margin: auto; }
        input, select { width: 100%; padding: 8px; margin-bottom: 12px; box-sizing: border-box; }
        label { font-weight: bold; }
        input[type="submit"] { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
        
        /* Estilo do botão de voltar */
        .back-btn {
            background-color: #007BFF; /* Azul bonito */
            color: white;
            padding: 12px 20px;
            text-align: center;
            border-radius: 5px;
            display: inline-block;  /* Modificado para não ocupar toda a largura */
            font-size: 16px;
            text-decoration: none;
            width: auto; /* Ajustado para tamanho automático */
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        /* Ajuste no campo de texto */
        input[type="text"] {
            width: calc(100% - 16px); /* Ajuste da largura do campo */
        }
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

    <!-- Botão de Voltar ao Painel -->
    <a href="painel_jogador.php" class="back-btn">Voltar ao Painel</a>
</div>

</body>
</html>
