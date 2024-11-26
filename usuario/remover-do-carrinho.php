<?php
session_start();
include '../config/conexao.php';


// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../login.php');
  exit;
}


$usuario_id = $_SESSION['usuario_id'];
$produto_id = $_POST['produto_id'];


// Remove o produto específico do carrinho
$sql_remover = "DELETE FROM carrinho WHERE usuario_id = ? AND produto_id = ?";
$stmt_remover = $conn->prepare($sql_remover);
$stmt_remover->bind_param("ii", $usuario_id, $produto_id);


if ($stmt_remover->execute()) {
  // Redireciona de volta para o carrinho após a remoção
  header('Location: carrinho.php');
  exit;
} else {
  echo "Erro ao remover o item do carrinho.";
}