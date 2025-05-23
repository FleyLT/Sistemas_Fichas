<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$ficha_id = $_GET['ficha_id'] ?? null;
$ficha_selecionada = null;
$xp_atual = 0;
$pode_upar = false;

// Buscar todas as fichas do jogador
$fichas = [];
$res = mysqli_query($conn, "SELECT id, nome_personagem FROM fichas WHERE usuario_id = $usuario_id");
while ($row = mysqli_fetch_assoc($res)) {
    $fichas[] = $row;
}

// Se uma ficha foi selecionada
if ($ficha_id) {
    $sql = "SELECT * FROM fichas WHERE id = $ficha_id AND usuario_id = $usuario_id";
    $res = mysqli_query($conn, $sql);
    $ficha_selecionada = mysqli_fetch_assoc($res);

    if (!$ficha_selecionada) {
        echo "<p style='color:red;'>Ficha não encontrada.</p>";
        exit;
    }

    $nivel_atual = $ficha_selecionada['nivel'];
    $proximo_nivel = $nivel_atual + 1;

    // Buscar XP ganho
    $res1 = mysqli_query($conn, "SELECT SUM(xp_ganho) AS total FROM xp_historico WHERE ficha_id = $ficha_id");
    $xp_ganho = (int)(mysqli_fetch_assoc($res1)['total'] ?? 0);

    // Buscar XP gasto
    $res2 = mysqli_query($conn, "SELECT SUM(xp_gasto) AS total FROM xp_gastos WHERE ficha_id = $ficha_id");
    $xp_gasto = (int)(mysqli_fetch_assoc($res2)['total'] ?? 0);

    $xp_atual = $xp_ganho - $xp_gasto;

    // Buscar custo do próximo nível
    $res = mysqli_query($conn, "SELECT * FROM nivel_progressao WHERE nivel = $proximo_nivel");
    $progressao = mysqli_fetch_assoc($res);

    if ($progressao && $xp_atual >= $progressao['xp_necessario']) {
        $pode_upar = true;
    }

    // Upando de nível
    if (isset($_GET['upar']) && $pode_upar) {
        $novo_nivel = $proximo_nivel;
        $novos_pontos = $ficha_selecionada['pontos_livres'] + $progressao['pontos_disponiveis'];

        // Registrar gasto de XP
        $motivo = "Upou para o nível $novo_nivel";
        mysqli_query($conn, "INSERT INTO xp_gastos (ficha_id, xp_gasto, motivo) VALUES ($ficha_id, {$progressao['xp_necessario']}, '$motivo')");

        // Atualizar ficha
        mysqli_query($conn, "UPDATE fichas SET nivel = $novo_nivel, pontos_livres = $novos_pontos WHERE id = $ficha_id");

        header("Location: evoluir_ficha.php?ficha_id=$ficha_id&msg=upado");
        exit;
    }

    // 🔥 Buscar atributos
    $sql_atributos = "SELECT * FROM ficha_atributos WHERE ficha_id = $ficha_id";
    $res_atributos = mysqli_query($conn, $sql_atributos);
    $atributos = [];
    if ($res_atributos) {
        while ($row = mysqli_fetch_assoc($res_atributos)) {
            $atributos[] = $row;
        }
    } else {
        echo "<p style='color:red;'>Erro ao buscar atributos: " . mysqli_error($conn) . "</p>";
    }

    // 🔧 Distribuir pontos de atributos
    if (isset($_POST['distribuir_atributos'])) {
        $distribuir = $_POST['atributos'] ?? [];
        $total_gasto = array_sum($distribuir);

        if ($total_gasto <= $ficha_selecionada['pontos_livres']) {
            foreach ($distribuir as $atributo_id => $valor) {
                $valor = intval($valor);
                if ($valor > 0) {
                    mysqli_query($conn, "UPDATE ficha_atributos SET valor = valor + $valor WHERE id = $atributo_id AND ficha_id = $ficha_id");
                }
            }

            // Atualizar pontos livres
            $novos_pontos = $ficha_selecionada['pontos_livres'] - $total_gasto;
            mysqli_query($conn, "UPDATE fichas SET pontos_livres = $novos_pontos WHERE id = $ficha_id");

            header("Location: evoluir_ficha.php?ficha_id=$ficha_id&msg=atributos_upados");
            exit;
        } else {
            echo "<p style='color:red;'>Você não tem pontos livres suficientes.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Evoluir Ficha</title>
    <style>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #e9ecef;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 700px;
        margin: auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }

    h2, h3 {
        color: #343a40;
        text-align: center;
    }

    label {
        font-weight: bold;
        color: #495057;
    }

    select, input[type="submit"], a.button {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background-color: #f8f9fa;
        color: #212529;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    select:focus, input[type="number"]:focus {
        border-color: #007bff;
        outline: none;
    }

    input[type="submit"]:hover, a.button:hover {
        background-color: #dee2e6;
        transform: scale(1.02);
    }

    input[type="number"] {
        width: 80px;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 6px;
    }

    .atributo-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .info {
        margin-top: 20px;
    }

    .upado {
        color: #28a745;
        font-weight: bold;
        text-align: center;
        margin-top: 10px;
    }

    .btn-voltar {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        padding: 14px 20px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: bold;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn-voltar:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    a.button {
        display: block;
        background-color: #28a745;
        color: white;
        text-align: center;
        border: none;
        text-decoration: none;
    }

    a.button:hover {
        background-color: #218838;
    }

    hr {
        border: none;
        border-top: 1px solid #dee2e6;
        margin: 20px 0;
    }
</style>

    </style>
</head>
<body>
    <a href="painel_jogador.php" class="btn-voltar">🏠 Voltar ao Painel</a>

<div class="container">
    <h2>⚔️ Evoluir Ficha</h2>

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

    <?php if ($ficha_selecionada): ?>
        <div class="info">
            <h3><?= htmlspecialchars($ficha_selecionada['nome_personagem']) ?></h3>
            <p><strong>Nível atual:</strong> <?= $ficha_selecionada['nivel'] ?></p>
            <p><strong>XP atual:</strong> <?= $xp_atual ?></p>
            <p><strong>Pontos livres:</strong> <?= $ficha_selecionada['pontos_livres'] ?></p>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'upado'): ?>
                <p class="upado">✅ Ficha evoluída com sucesso!</p>
            <?php endif; ?>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'atributos_upados'): ?>
                <p class="upado">✅ Atributos atualizados com sucesso!</p>
            <?php endif; ?>

            <?php if ($pode_upar): ?>
                <p><strong>Pronto para upar para o nível <?= $proximo_nivel ?>!</strong></p>
                <a href="evoluir_ficha.php?ficha_id=<?= $ficha_id ?>&upar=1" class="button" style="background:#28a745; color:white; text-align:center; display:block; text-decoration:none;">⬆️ Upar Agora</a>
            <?php else: ?>
                <p><strong>XP necessário para o próximo nível:</strong> <?= $progressao['xp_necessario'] ?? 'N/A' ?></p>
                <p><em>Você ainda não tem XP suficiente para upar.</em></p>
            <?php endif; ?>
        </div>

        <hr>

        <div class="info">
            <h3>🔧 Distribuir Pontos de Atributo</h3>
            <form method="POST">
                <?php foreach ($atributos as $atributo): ?>
            <div class="atributo-box">
                <label><?= htmlspecialchars(ucfirst($atributo['atributo'])) ?> (Atual: <?= $atributo['valor'] ?>)</label>
                <input type="number" name="atributos[<?= $atributo['id'] ?>]" min="0" max="<?= $ficha_selecionada['pontos_livres'] ?>" value="0">
            </div>
        <?php endforeach; ?>
        <input type="submit" name="distribuir_atributos" value="Distribuir Pontos">
    </form>
</div>
    <?php endif; ?>
</div>

</body>
</html>
