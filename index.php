<?php
session_start(); // Iniciar a sessão para armazenar dados do usuário

// Verificando se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Incluindo a conexão com o banco
    include('conexao.php');

    // Recebendo os dados do formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Prevenindo SQL Injection com prepared statements
    $query = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $query->bind_param("s", $usuario);
    $query->execute();
    $result = $query->get_result();

    // Verificando se o usuário foi encontrado
    if ($result->num_rows > 0) {
        // O usuário existe, agora verificamos a senha
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user['senha'])) { // Verificando a senha usando password_verify
            // Autenticação bem-sucedida, criando a sessão
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_email'] = $user['email'];
            $_SESSION['usuario_papel'] = $user['papel']; // Aqui estamos armazenando o papel do usuário

            // Redirecionando para o dashboard após o login
            header("Location: dashboard.php");
            exit();
        } else {
            $mensagem = "Usuário/Senha incorretos!";
        }
    } else {
        $mensagem = "Usuário/Senha incorretos!";
    }

    // Fechando a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Ficha</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
    <form action="" method="post">
        <div class="login-box">
            <h2>SEJA BEM-VINDO</h2>  
            <input type="text" name="usuario" placeholder="email@gmail.com" required />
            <input type="password" name="senha" placeholder="Senha" required />
            <button type="submit">LOGAR</button>
            <a href="registro.html" class="register-btn">Registre-se</a>

            <?php if (isset($mensagem)): ?>
                <p style="color: red;"><?php echo $mensagem; ?></p>
            <?php endif; ?>
        </div>
    </form>
</body>
</html>
