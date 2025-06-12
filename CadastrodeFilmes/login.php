<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login do Usuário</title>

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
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login</h2>
    <form action="processa_login.php" method="POST">
        <div class="mb-3">
            <label for="login" class="form-label">Login:</label>
            <input type="text" id="login" name="login" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" id="senha" name="senha" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-rosa w-100">Entrar</button>
    </form>

    <p class="mt-3 text-center">
        <a href="cadastro.php" class="link-rosa">Criar nova conta</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>