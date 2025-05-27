<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    header("Location: index.php");
    exit;
}

// Buscar fichas do jogador
$fichas = [];
$res = mysqli_query($conn, "SELECT id, nome_personagem FROM fichas WHERE usuario_id = $usuario_id");
while ($row = mysqli_fetch_assoc($res)) {
    $fichas[] = $row;
}

// Verificar ficha selecionada
$ficha_id = isset($_GET['ficha_id']) ? intval($_GET['ficha_id']) : null;

// Remover item, se solicitado
if (isset($_GET['remover']) && isset($_GET['item_id']) && $ficha_id) {
    $item_id = intval($_GET['item_id']);
    mysqli_query($conn, "DELETE FROM ficha_inventarios WHERE ficha_id = $ficha_id AND item_id = $item_id");
    header("Location: inventario.php?ficha_id=$ficha_id");
    exit;
}

// Buscar inventário da ficha
$itens = [];

if ($ficha_id) {
    $sql = "
        SELECT fi.id, fi.quantidade, i.nome, i.descricao, fi.item_id
        FROM ficha_inventarios fi
        JOIN itens i ON fi.item_id = i.id
        WHERE fi.ficha_id = $ficha_id
    ";
    $res = mysqli_query($conn, $sql);

    if (!$res) {
        die("Erro na consulta do inventário: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $itens[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventário</title>
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

    .container {
        max-width: 960px;
        margin: auto;
        background-color: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }

    .container:hover {
        transform: scale(1.005);
    }

    h2 {
        text-align: center;
        color: #212529;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #495057;
    }

    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 10px;
        background-color: #f8f9fa;
        color: #495057;
        transition: border-color 0.3s, background-color 0.3s;
    }

    select:focus {
        border-color: #0d6efd;
        background-color: #fff;
        outline: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 24px;
        background-color: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    th {
        background-color: #f8f9fa;
        color: #343a40;
        font-weight: 600;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #f1f3f5;
    }

    .btn-remove {
        background-color: #dc3545;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s, transform 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-remove:hover {
        background-color: #bb2d3b;
        transform: scale(1.05);
    }

    p em {
        color: #6c757d;
        display: block;
        text-align: center;
        margin-top: 20px;
    }

    .btn-voltar {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #0d6efd;
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

    @media (max-width: 600px) {
        th, td {
            padding: 10px;
        }

        .btn-voltar {
            padding: 10px 16px;
        }
    }
</style>
</head>
<body>
    <a href="painel_jogador.php" class="btn-voltar">🏠 Voltar ao Painel</a>

<div class="container">
    <h2>🎒 Inventário</h2>

    <form method="GET" action="">
        <label>Selecione a ficha:</label>
        <select name="ficha_id" onchange="this.form.submit()" required>
            <option value="">-- Escolha uma ficha --</option>
            <?php foreach ($fichas as $ficha): ?>
                <option value="<?= $ficha['id'] ?>" <?= ($ficha_id == $ficha['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ficha['nome_personagem']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($ficha_id): ?>
        <?php if (count($itens) > 0): ?>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Ação</th>
                </tr>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= htmlspecialchars($item['descricao']) ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>
                            <a class="btn-remove"
                               href="inventario.php?ficha_id=<?= $ficha_id ?>&remover=1&item_id=<?= $item['item_id'] ?>"
                               onclick="return confirm('Tem certeza que deseja remover este item?');">
                                🗑️ Colocar fora
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p><em>Este inventário está vazio.</em></p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
