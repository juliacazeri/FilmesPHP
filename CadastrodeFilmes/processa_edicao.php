<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

//Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $titulo = trim($_POST['titulo']);
    $ano = filter_var($_POST['ano'], FILTER_VALIDATE_INT);
    $erros = [];


    //Validação de dados
    if (!$id) {
        $erros[] = "ID do filme inválido.";
    }
    if (empty($titulo)) {
        $erros[] = "O título é obrigatório.";
    }
    if (!$ano || $ano < 1800 || $ano > intval(date("Y")) + 1) {
        $erros[] = "Ano inválido.";
    }

    if (count($erros) > 0):
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Erro ao editar filme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <?php foreach ($erros as $erro): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endforeach; ?>
        <a href="editar_filme.php?id=<?php echo htmlspecialchars($id); ?>" class="btn btn-danger">Voltar</a>
    </div>
</body>
</html>
<?php
        exit;
    endif;

    $conn = conectar_banco();

    //Buscar o ID do usuário pela sessão (login)
    $login = $_SESSION['usuario'];

    $sqlUser = "SELECT id FROM tb_usuarios WHERE login = ?";
    $stmtUser = mysqli_prepare($conn, $sqlUser);
    if (!$stmtUser) {
        die("Erro preparando consulta de usuário: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmtUser, "s", $login);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);
    $usuario = mysqli_fetch_assoc($resultUser);
    mysqli_stmt_close($stmtUser);

    if ($usuario) {
        $usuarioId = $usuario['id'];

        $sqlUpdate = "UPDATE tb_filmes SET titulo = ?, ano = ? WHERE id = ? AND usuario_id = ?";
        $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
        if (!$stmtUpdate) {
            die("Erro preparando atualização: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmtUpdate, "siii", $titulo, $ano, $id, $usuarioId);
        mysqli_stmt_execute($stmtUpdate);

        //Tratamento quando não atualiza nada
        if (mysqli_stmt_affected_rows($stmtUpdate) > 0) {
            mysqli_stmt_close($stmtUpdate);
            mysqli_close($conn);
            header("Location: listar_filmes.php");
            exit;
        } else {
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Erro ao atualizar filme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="alert alert-danger" role="alert">
            Erro: Filme não encontrado ou não pertence a você.
        </div>
        <a href="listar_filmes.php" class="btn btn-danger">Voltar</a>
    </div>
</body>
</html>
<?php
        }

        mysqli_stmt_close($stmtUpdate);
    } else {
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Usuário não encontrado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="alert alert-danger" role="alert">
            Usuário não encontrado.
        </div>
        <a href="login.php" class="btn btn-danger">Fazer login</a>
    </div>
</body>
</html>
<?php
    }

    mysqli_close($conn);
} else {
    header("Location: listar_filmes.php");
    exit;
}
?>