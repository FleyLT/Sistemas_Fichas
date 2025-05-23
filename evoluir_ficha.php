<?php
session_start();
require 'conexao.php'; // Supondo que você tenha uma conexão com o banco já configurada

// Verifica se o usuário está logado
$usuario_id = $_SESSION['usuario_id'] ?? null;
if (!$usuario_id) {
    header("Location: login.php");
    exit;
}

// Buscar fichas do usuário
$fichas = [];
$sql = "SELECT id, nome_personagem, classe_id, nivel FROM fichas WHERE usuario_id = $usuario_id";
$res = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $fichas[] = $row;
}

// Caso o formulário de evolução tenha sido enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se o campo 'ficha_id' foi enviado
    if (isset($_POST['ficha_id']) && !empty($_POST['ficha_id'])) {
        $ficha_id = (int)$_POST['ficha_id'];
        
        // Recuperar a ficha selecionada
        $sql = "SELECT * FROM fichas WHERE id = $ficha_id AND usuario_id = $usuario_id";
        $ficha_res = mysqli_query($conn, $sql);
        $ficha = mysqli_fetch_assoc($ficha_res);

        if ($ficha) {
            // Exibir atributos da ficha
            $sql_atributos = "SELECT * FROM ficha_atributos WHERE ficha_id = $ficha_id";
            $atributos_res = mysqli_query($conn, $sql_atributos);
            $atributos = [];
            while ($atributo = mysqli_fetch_assoc($atributos_res)) {
                $atributos[] = $atributo;
            }

            // Calcular XP disponível (XP ganho - XP gasto)
            $sql_xp_ganho = "SELECT SUM(xp_ganho) AS total_xp_ganho FROM xp_historico WHERE ficha_id = $ficha_id";
            $xp_ganho_res = mysqli_query($conn, $sql_xp_ganho);
            $xp_ganho = mysqli_fetch_assoc($xp_ganho_res)['total_xp_ganho'] ?? 0;

            $sql_xp_gasto = "SELECT SUM(xp_gasto) AS total_xp_gasto FROM xp_gastos WHERE ficha_id = $ficha_id";
            $xp_gasto_res = mysqli_query($conn, $sql_xp_gasto);
            $xp_gasto = mysqli_fetch_assoc($xp_gasto_res)['total_xp_gasto'] ?? 0;

            // XP disponível
            $xp_disponivel = $xp_ganho - $xp_gasto;

            // Exibir os dados da ficha e XP
            echo "<h2>📈 Evoluir Ficha: " . htmlspecialchars($ficha['nome_personagem']) . "</h2>";
            echo "<p><strong>Classe:</strong> " . htmlspecialchars($ficha['classe_id']) . "</p>";
            echo "<p><strong>Nível:</strong> " . htmlspecialchars($ficha['nivel']) . "</p>";
            echo "<p><strong>XP disponível:</strong> $xp_disponivel XP</p>";
            
            echo "<h3>Atributos:</h3>";
            foreach ($atributos as $atributo) {
                echo "<p><strong>" . ucfirst($atributo['atributo']) . ":</strong> " . $atributo['valor'] . "</p>";
            }

            // Se o usuário tiver XP, ele pode evoluir os atributos
            if ($xp_disponivel > 0) {
                echo "<form method='POST' action='evoluir_ficha.php'>
                        <label>Escolha o atributo a ser melhorado:</label>
                        <select name='atributo' required>
                            <option value=''>Selecione</option>";
                foreach ($atributos as $atributo) {
                    echo "<option value='" . $atributo['atributo'] . "'>" . ucfirst($atributo['atributo']) . "</option>";
                }
                echo "</select>";
                
                echo "<label>Quantidade de XP para gastar:</label>
                      <input type='number' name='xp_gasto' min='1' max='$xp_disponivel' value='1' required>
                      <input type='submit' value='Evoluir Atributo'>
                      </form>";
            } else {
                echo "<p>Você não possui XP disponível para evoluir.</p>";
            }
        } else {
            echo "<p>Ficha não encontrada ou você não tem permissão para editá-la.</p>";
        }
    } else {
        echo "<p>Por favor, selecione uma ficha.</p>";
    }
} else {
    // Se o jogador ainda não selecionou uma ficha
    echo "<h2>Selecione uma Ficha para Evoluir:</h2>";
    echo "<form method='POST' action='evoluir_ficha.php'>";
    echo "<label>Escolha sua ficha:</label>";
    echo "<select name='ficha_id' required>";
    echo "<option value=''>Selecione</option>";
    foreach ($fichas as $ficha) {
        echo "<option value='" . $ficha['id'] . "'>" . htmlspecialchars($ficha['nome_personagem']) . " (Nível: " . $ficha['nivel'] . ")</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Selecionar Ficha'>";
    echo "</form>";
}
?>

<!-- Botão de Voltar -->
<form action="painel_jogador.php">
    <input type="submit" value="Voltar para o Painel" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
</form>
