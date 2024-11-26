<?php
session_start();

// Destruir todas as variáveis de sessão
$_SESSION = [];

session_destroy();

// Redirecionar o usuário para a página de login após o logout
header('Location: index.php');
exit;
