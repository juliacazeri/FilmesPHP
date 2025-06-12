<?php
session_start();

// Verifica se o usuário está logado, caso contrário redireciona para a página de login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Filmes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        a.link-rosa {
            color: #e91e63;
            text-decoration: none;
        }
        a.link-rosa:hover {
            color: #c2185b;
            text-decoration: underline;
        }
        hr {
            border-color: #e91e63;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 700px;">
    <?php
    require 'conexao.php';

    $conn = conectar_banco();  
    $login = $_SESSION['usuario'];

    // Prepara consulta para buscar o ID do usuário pelo login (query segura com prepared statement)
    $sqlUser = "SELECT id FROM tb_usuarios WHERE login = ?";
    $stmtUser = mysqli_prepare($conn, $sqlUser);
    if ($stmtUser === false) {
        die("Erro na preparação da query (usuário): " . mysqli_error($conn));
    }

    // Associa o parâmetro "s" (string) para evitar SQL Injection
    mysqli_stmt_bind_param($stmtUser, "s", $login);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);
    $usuario = mysqli_fetch_assoc($resultUser);
    mysqli_stmt_close($stmtUser);

    // Se o usuário foi encontrado, prossegue para buscar os filmes
    if ($usuario):
        $usuarioId = $usuario['id'];

        // Prepara consulta para buscar todos os filmes deste usuário
        $sqlFilmes = "SELECT id, titulo, ano FROM tb_filmes WHERE usuario_id = ?";
        $stmtFilmes = mysqli_prepare($conn, $sqlFilmes);
        if ($stmtFilmes === false) {
            die("Erro na preparação da query (filmes): " . mysqli_error($conn));
        }

        // Liga o parâmetro "i" (inteiro) do ID do usuário
        mysqli_stmt_bind_param($stmtFilmes, "i", $usuarioId);
        mysqli_stmt_execute($stmtFilmes);
        $resultFilmes = mysqli_stmt_get_result($stmtFilmes);
        $filmes = mysqli_fetch_all($resultFilmes, MYSQLI_ASSOC);
        mysqli_stmt_close($stmtFilmes);
    ?>
    
    <!-- Título exibindo o login do usuário -->
    <h2 class="mb-4 text-center">Filmes cadastrados por <?php echo htmlspecialchars($login); ?></h2>

    <?php if (count($filmes) > 0): ?> <!-- Se houver filmes cadastrados -->
        <ul class="list-group">
            <?php foreach ($filmes as $filme): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>

                     <!-- Exibe título e ano do filme, escapando para evitar XSS -->
                        <strong><?php echo htmlspecialchars($filme['titulo']); ?></strong> — Ano: <?php echo htmlspecialchars($filme['ano']); ?>
                    </div>
                    <a href="editar_filme.php?id=<?php echo $filme['id']; ?>" class="link-rosa">✏️ Editar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-center">Você ainda não cadastrou nenhum filme.</p>
    <?php endif; ?>

    <?php else: ?>
        <p class="text-center text-danger">Erro: usuário não encontrado.</p>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>

    <p class="mt-4 text-center">
        <a href="painel.php" class="link-rosa">&#8592; Voltar ao painel</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
