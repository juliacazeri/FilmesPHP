<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Usuário</title>
    
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
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p class="text-center">Você está logado no sistema de filmes.</p>

    <ul class="list-group">
        <li class="list-group-item"><a href="cadastro_filme.php" class="link-rosa">Cadastrar novo filme</a></li>
        <li class="list-group-item"><a href="listar_filmes.php" class="link-rosa">Ver meus filmes</a></li>
        <li class="list-group-item"><a href="logout.php" class="link-rosa">Sair</a></li>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>