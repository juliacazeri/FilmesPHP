<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo']);
    $ano = trim($_POST['ano']);
    $erros = [];

    if (empty($titulo)) {
        $erros[] = "O título é obrigatório.";
    }
    if (empty($ano)) {
        $erros[] = "O ano é obrigatório.";
    } elseif (!ctype_digit($ano) || strlen($ano) != 4) {
        $erros[] = "Informe um ano válido com 4 dígitos.";
    }

    if (count($erros) > 0):
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Erro no cadastro do filme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <?php foreach ($erros as $erro): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endforeach; ?>
        <a href="cadastro_filme.php" class="btn btn-danger">Voltar</a>
    </div>
</body>
</html>
<?php
        exit;
    endif;

    $conn = conectar_banco();

    // Busca o ID do usuário logado
    $login = $_SESSION['usuario'];
    $sqlUser = "SELECT id FROM tb_usuarios WHERE login = ?";
    $stmtUser = mysqli_prepare($conn, $sqlUser);
    if ($stmtUser === false) {
        die("Erro na preparação da query (usuário): " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmtUser, "s", $login);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);
    $usuario = mysqli_fetch_assoc($resultUser);

    if ($usuario) {
        $usuarioId = $usuario['id'];

        // Insere o filme com título, ano e usuario_id (corrigido)
        $sql = "INSERT INTO tb_filmes (titulo, ano, usuario_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt === false) {
            die("Erro na preparação da query (filme): " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "sii", $titulo, $ano, $usuarioId);

        if (mysqli_stmt_execute($stmt)):
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Filme cadastrado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="alert alert-success" role="alert">
            🎬 Filme cadastrado com sucesso!
        </div>
        <a href="cadastro_filme.php" class="btn btn-primary me-2">Cadastrar outro</a>
        <a href="painel.php" class="btn btn-secondary">Voltar ao painel</a>
    </div>
</body>
</html>
<?php
        else:
            echo "<div class='alert alert-danger'>Erro ao cadastrar o filme: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
        endif;

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Erro: usuário não encontrado.</div>";
    }

    mysqli_stmt_close($stmtUser);
    mysqli_close($conn);
}
?>
