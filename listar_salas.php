<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

$sql = "SELECT s.*, u.nome AS mestre_nome 
        FROM salas s
        INNER JOIN usuarios u ON u.id = s.mestre_id
        WHERE s.status = 'ativa'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>
            <tr>
                <th>Nome da Sala</th>
                <th>Código de Acesso</th>
                <th>Mestre</th>
                <th>Data de Criação</th>
                <th>Ação</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".htmlspecialchars($row['nome'])."</td>
                <td>".htmlspecialchars($row['codigo_acesso'])."</td>
                <td>".htmlspecialchars($row['mestre_nome'])."</td>
                <td>".date('d/m/Y', strtotime($row['data_criacao']))."</td>
                <td><a class='btn' href='participar_sala.php?sala_id=".$row['id']."'>Participar</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>⚠️ Nenhuma sala disponível no momento.</p>";
}
?>
