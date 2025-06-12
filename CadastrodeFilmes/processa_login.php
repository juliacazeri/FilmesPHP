<?php
require_once 'conexao.php';

session_start();

$conn = conectar_banco();

//Captura de dados do formulário
$login = trim($_POST['login']);
$senha = $_POST['senha'];

//Busca do usuário no banco
$sql = "SELECT * FROM tb_usuarios WHERE login = ?";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    die("Erro na preparação da query: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$usuario = mysqli_fetch_assoc($result);

//Validação da senha
if ($usuario && $senha === $usuario['senha']) {
    //Login bem-sucedido
    $_SESSION['usuario'] = $usuario['login'];
    header("Location: painel.php");
    exit;
} else {
    // Mensagem de erro
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Login inválido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .btn-pink {
            background-color: #e83e8c;
            color: white;
        }
        .btn-pink:hover {
            background-color: #c72e6f;
            color: white;
        }
        .alert-pink {
            background-color: #f8d7da;
            color: #842029;
            border-color: #f5c2c7;
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <div class="alert alert-pink" role="alert">
            Login ou senha incorretos.
        </div>
        <a href="login.php" class="btn btn-pink">Tentar novamente</a>
    </div>
</body>
</html>
<?php
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
