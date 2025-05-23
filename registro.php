<?php
// Pegar dados da conexao
require 'conexao.php';


// Receber dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verificar se o email já existe
$sql_check_email = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql_check_email);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Erro: Este email já está registrado.";
    exit;
}

// Criptografar a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Inserir os dados do usuário no banco de dados
$sql = "INSERT INTO usuarios (nome, email, senha, papel) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$papel = 'jogador';  // O papel pode ser definido como "jogador" por padrão
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $papel);

if ($stmt->execute()) {
    echo "Registro realizado com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
