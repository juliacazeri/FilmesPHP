<?php
session_start();
// Verifica se o usuário está logado, checando se a variável de sessão 'usuario' existe
// Se não existir, redireciona para a página de login para proteger o acesso
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Filme</title>

    <!-- Link para o CSS do Bootstrap (framework para estilização e responsividade) -->
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
    </style>
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Cadastrar Novo Filme</h2>
    <form action="processa_filme.php" method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" id="titulo" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ano" class="form-label">Ano:</label>
            <input type="number" id="ano" name="ano" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-rosa w-100">Cadastrar Filme</button>
    </form>

    <p class="mt-3 text-center">
        <a href="painel.php" class="text-decoration-none" style="color: #e91e63;">&#8592; Voltar ao painel</a>
    </p>
</div>

<!-- Script JavaScript do Bootstrap para funcionalidades interativas -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>