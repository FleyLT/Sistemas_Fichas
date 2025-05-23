<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    header("Location: index.php");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    if ($tipo === 'habilidade') {
        $rolagem_dano = mysqli_real_escape_string($conn, $_POST['rolagem_dano']);
        $tipo_entrada = mysqli_real_escape_string($conn, $_POST['tipo_habilidade']);
        $custo_xp = intval($_POST['custo_xp']);

        $sql = "INSERT INTO sugestoes (usuario_id, tipo, nome, descricao, rolagem_dano, tipo_item_ou_habilidade, custo_xp)
                VALUES ($usuario_id, 'habilidade', '$nome', '$descricao', '$rolagem_dano', '$tipo_entrada', $custo_xp)";

    } elseif ($tipo === 'item') {
        $tipo_entrada = mysqli_real_escape_string($conn, $_POST['tipo_item']);
        $peso = floatval($_POST['peso']);
        $valor = floatval($_POST['valor']);
        $raridade = mysqli_real_escape_string($conn, $_POST['raridade']);
        $imagem_link = mysqli_real_escape_string($conn, $_POST['imagem_link']);

        $sql = "INSERT INTO sugestoes (usuario_id, tipo, nome, descricao, tipo_item_ou_habilidade, peso, valor, raridade, imagem_link)
                VALUES ($usuario_id, 'item', '$nome', '$descricao', '$tipo_entrada', $peso, $valor, '$raridade', '$imagem_link')";
    }

    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Sugestão enviada com sucesso! Agora aguarde a análise do mestre.";
    } else {
        $msg = "❌ Erro ao enviar sugestão.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sugerir Conteúdo</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: #f1f3f5;
        padding: 20px;
        margin: 0;
    }

    .container {
        max-width: 800px;
        background: white;
        margin: auto;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #212529;
        margin-bottom: 24px;
    }

    label {
        display: block;
        margin-top: 16px;
        font-weight: 600;
        color: #495057;
    }

    select, input, textarea {
        width: 100%;
        padding: 12px;
        margin-top: 8px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background-color: #fff;
        transition: border-color 0.3s;
    }

    select:focus, input:focus, textarea:focus {
        border-color: #0d6efd;
        outline: none;
    }

    .hidden {
        display: none;
    }

    .msg {
        margin-top: 20px;
        padding: 12px;
        background: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
    }

    .msg.error {
        background: #f8d7da;
        color: #842029;
        border-color: #f5c2c7;
    }

    .btn {
        background: #198754;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
        margin-top: 24px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn:hover {
        background: #157347;
        transform: translateY(-2px);
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
        background: #007bff;
        transform: scale(1.07);
    }

    @media (max-width: 600px) {
        .container {
            padding: 20px;
        }
    }
</style>

    <script>
        function toggleForm() {
            const tipo = document.getElementById('tipo').value;
            document.getElementById('form-habilidade').style.display = (tipo === 'habilidade') ? 'block' : 'none';
            document.getElementById('form-item').style.display = (tipo === 'item') ? 'block' : 'none';
        }
    </script>
</head>
<body>

<div class="container">
    <h2>✨ Sugerir Conteúdo</h2>

    <?php if ($msg): ?>
        <p class="msg"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Tipo de Sugestão:</label>
        <select name="tipo" id="tipo" onchange="toggleForm()" required>
            <option value="">-- Escolha --</option>
            <option value="habilidade">Habilidade</option>
            <option value="item">Item</option>
        </select>

        <!-- Comum -->
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Descrição:</label>
        <textarea name="descricao" rows="4" required></textarea>

        <!-- Habilidade -->
        <div id="form-habilidade" class="hidden">
            <label>Rolagem de Dano (ex.: 2d6+3):</label>
            <input type="text" name="rolagem_dano">

            <label>Tipo (ex.: magia, ataque, suporte):</label>
            <input type="text" name="tipo_habilidade">

            <label>Custo de XP:</label>
            <input type="number" name="custo_xp" min="0">
        </div>

        <!-- Item -->
        <div id="form-item" class="hidden">
            <label>Tipo do Item (ex.: arma, armadura, poção):</label>
            <input type="text" name="tipo_item">

            <label>Peso:</label>
            <input type="number" step="0.01" name="peso" min="0">

            <label>Valor em moedas:</label>
            <input type="number" step="0.01" name="valor" min="0">

            <label>Raridade (comum, incomum, raro, épico, lendário):</label>
            <input type="text" name="raridade">

            <label>Link da Imagem:</label>
            <input type="url" name="imagem_link">
        </div>

        <button type="submit" class="btn">🚀 Enviar Sugestão</button>

        <a href="painel_jogador.php" class="btn-voltar">🏠 Voltar ao Painel</a>
    </form>
</div>

</body>
</html>
