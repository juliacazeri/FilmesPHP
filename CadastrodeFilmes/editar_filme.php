<?php
session_start();

// Verifica se o usuário está logado, caso contrário redireciona para a página de login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require 'conexao.php';

// Cria uma conexão com o banco de dados usando a função definida em conexao.php
$conn = conectar_banco(); 

// Verifica se o parâmetro 'id' foi passado via URL, que identifica o filme a ser editado
if (!isset($_GET['id'])) {
    echo "<p>ID de filme inválido.</p>";
    exit;
}

$id = $_GET['id'];
$login = $_SESSION['usuario'];

// Prepara uma consulta segura para buscar o ID do usuário no banco pelo login
$sqlUser = "SELECT id FROM tb_usuarios WHERE login = ?";
$stmtUser = mysqli_prepare($conn, $sqlUser);
if ($stmtUser === false) {
    die("Erro na preparação da query (usuário): " . mysqli_error($conn));
}

// Liga o parâmetro "s" (string) com o login do usuário para prevenir SQL Injection
mysqli_stmt_bind_param($stmtUser, "s", $login);
mysqli_stmt_execute($stmtUser);
$resultUser = mysqli_stmt_get_result($stmtUser);
$usuario = mysqli_fetch_assoc($resultUser);
mysqli_stmt_close($stmtUser);

// Verifica se o usuário foi encontrado no banco (por segurança)
if (!$usuario) {
    echo "<p>Usuário não encontrado.</p>";
    exit;
}

$usuarioId = $usuario['id'];

// Prepara uma consulta para buscar o filme pelo ID e verificar se pertence ao usuário
$sqlFilme = "SELECT id, titulo, ano FROM tb_filmes WHERE id = ? AND usuario_id = ?";
$stmtFilme = mysqli_prepare($conn, $sqlFilme);
if ($stmtFilme === false) {
    die("Erro na preparação da query (filme): " . mysqli_error($conn));
}

// Liga os parâmetros "ii" (dois inteiros): id do filme e id do usuário para validação
mysqli_stmt_bind_param($stmtFilme, "ii", $id, $usuarioId); // Executa a consulta para buscar o filme
mysqli_stmt_execute($stmtFilme);
$resultFilme = mysqli_stmt_get_result($stmtFilme);
$filme = mysqli_fetch_assoc($resultFilme);// Extrai os dados do filme em array associativo
mysqli_stmt_close($stmtFilme);

// Verifica se o filme foi encontrado e pertence ao usuário logado
if (!$filme) {
    echo "<p>Filme não encontrado ou não pertence a você.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Filme</title>

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
        a.link-rosa {
            color: #e91e63;
            text-decoration: none;
        }
        a.link-rosa:hover {
            color: #c2185b;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Editar Filme</h2>

    <form action="processa_edicao.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($filme['id']); ?>">

        <div class="mb-3">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo htmlspecialchars($filme['titulo']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="ano" class="form-label">Ano:</label>
            <input type="text" name="ano" id="ano" class="form-control" maxlength="4" value="<?php echo htmlspecialchars($filme['ano']); ?>" required>
        </div>

        <button type="submit" class="btn btn-rosa w-100">Salvar alterações</button>
    </form>

    <p class="mt-3 text-center">
        <a href="listar_filmes.php" class="link-rosa">&#8592; Voltar à lista</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>