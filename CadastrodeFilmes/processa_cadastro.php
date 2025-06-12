<?php
require 'conexao.php';

$conn = conectar_banco();

// Recebe e limpa os campos
$login = trim($_POST['login']);
$senha = trim($_POST['senha']);
$email = trim($_POST['email']);

$erros = []; // Array para armazenar mensagens de erro

if (empty($login)) {
    $erros[] = "O campo login é obrigatório.";
}
if (empty($senha)) {
    $erros[] = "O campo senha é obrigatório.";
}
if (empty($email)) {
    $erros[] = "O campo e-mail é obrigatório.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-rosa {
            background-color: #e91e63;
            color: white;
        }
        .btn-rosa:hover {
            background-color: #c2185b;
            color: white;
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 480px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
<?php
if (count($erros) > 0) {
    echo '<div class="alert alert-danger" role="alert">';
    foreach ($erros as $erro) {
        echo htmlspecialchars($erro) . "<br>"; // Exibe os erros de forma segura
    }
    echo '</div>';
    echo '<a href="cadastro.php" class="btn btn-rosa">Voltar</a>';
    exit; // Interrompe o script se houver erro
}

$senhaSemHash = $senha;

$sql = "INSERT INTO tb_usuarios (login, senha, email) VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql); // Prepara a query com placeholders

//Verifica se a query foi preparada com sucesso
if ($stmt === false) {
    echo '<div class="alert alert-danger" role="alert">';
    echo "Erro na preparação da query: " . htmlspecialchars(mysqli_error($conn));
    echo '</div>';
    echo '<a href="cadastro.php" class="btn btn-rosa">Voltar</a>';
    exit;
}

mysqli_stmt_bind_param($stmt, "sss", $login, $senhaSemHash, $email);

//Execução da query
if (mysqli_stmt_execute($stmt)) {
    echo '<div class="alert alert-success" role="alert">';
    echo "Usuário cadastrado com sucesso!";
    echo '</div>';
    echo '<a href="login.php" class="btn btn-rosa">Ir para o login</a>';
} else {
    echo '<div class="alert alert-danger" role="alert">';
    echo "Erro ao cadastrar o usuário.";
    echo '</div>';
    echo '<a href="cadastro.php" class="btn btn-rosa">Voltar</a>';
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
