<?php
session_start();
session_unset();  // Limpa as variáveis da sessão
session_destroy(); // Destroi a sessão
header("Location: login.php");
exit;
?>