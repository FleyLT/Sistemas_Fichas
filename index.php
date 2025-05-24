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

            // Redirecionando para a página principal após login
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
    <title>Sistema Ficha - Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #0f172a;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #0f172a;
        }

        .login-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 24px;
            color: #0f172a;
        }

        .login-box input {
            width: 100%;
            padding: 12px 16px;
            margin: 10px 0;
            border: none;
            border-radius: 12px;
            outline: none;
            background: #e2e8f0;
            color: #0f172a;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .login-box input::placeholder {
            color: #64748b;
        }

        .login-box input:focus {
            background: #cbd5e1;
        }

        .login-box button {
            background: #2563eb;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            font-weight: 600;
            transition: background 0.3s, transform 0.2s;
        }

        .login-box button:hover {
            background: #1d4ed8;
            transform: scale(1.05);
        }

        .login-box .register-btn {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 20px;
            background: transparent;
            color: #2563eb;
            border: 1px solid #2563eb;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-box .register-btn:hover {
            background: #2563eb;
            color: white;
        }

        .error-message {
            margin-top: 12px;
            font-weight: bold;
            color: #e11d48;
        }
    </style>
</head>
<body>

    <form action="" method="post">
        <div class="login-box">
            <h2>Seja Bem-vindo</h2>
            <input type="text" name="usuario" placeholder="email@gmail.com" required />
            <input type="password" name="senha" placeholder="Senha" required />
            <button type="submit">Logar</button>
            <a href="registro.html" class="register-btn">Registre-se</a>

            <?php if (isset($mensagem)): ?>
                <p class="error-message"><?php echo $mensagem; ?></p>
            <?php endif; ?>
        </div>
    </form>

</body>
</html>
