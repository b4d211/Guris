<?php
session_start();
include '../config/conexao.php';


// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}


// Verifica se o ID do pedido foi passado como parâmetro
if (!isset($_GET['id'])) {
  echo "<p>ID do pedido não fornecido.</p>";
  include '../includes/footer.php';
  exit;
}


$pedido_id = $_GET['id'];


// Consulta para obter os detalhes do pedido, incluindo endereço do cliente
$sql_pedido = "SELECT p.*, u.nome, u.bairro, u.logradouro, u.numero, u.complemento
               FROM pedidos p
               JOIN usuarios u ON p.usuario_id = u.id
               WHERE p.id = ?";
$stmt_pedido = $conn->prepare($sql_pedido);
$stmt_pedido->bind_param("i", $pedido_id);
$stmt_pedido->execute();
$result_pedido = $stmt_pedido->get_result();


if ($result_pedido->num_rows == 0) {
  echo "<p>Pedido não encontrado ou você não tem permissão para visualizá-lo.</p>";
  include '../includes/footer.php';
  exit;
}


// Detalhes do pedido
$pedido = $result_pedido->fetch_assoc();


// Obtém os itens do pedido
$sql_itens = "SELECT i.quantidade, p.nome, p.preco
              FROM itens_pedido i
              JOIN produtos p ON i.produto_id = p.id
              WHERE i.pedido_id = ?";
$stmt_itens = $conn->prepare($sql_itens);
$stmt_itens->bind_param("i", $pedido_id);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();
?>


<!DOCTYPE html>
<html lang="pt-BR">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Detalhes da Venda - Fast Food</title>
  <link rel="stylesheet" href="../css/detalhe_venda.css">
  <link rel="stylesheet" href="../css/header.css">


</head>


<body>
  <?php include '../includes/header.php'; ?>
  <div class="container">
    <div>
      <img class="img" src="../imgs/detalhes.pedido2.png" alt="">

    </div>
    <h1 class="title">Detalhes do Pedido #<?= $pedido['id']; ?></h1>
    <div class="tudo">
    <div class="item1">
    <p><strong>Nome do Cliente:</strong> <?= $pedido['nome']; ?></p>
    <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></p>
    <p><strong>Total do Pedido:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></p>
    <p><strong>Status:</strong> <?= ucfirst($pedido['status']); ?></p>
    </div>

    <div class="item2">
    <h2>Endereço de Entrega</h2>
    <p><strong>Bairro:</strong> <?= $pedido['bairro']; ?></p>
    <p><strong>Logradouro:</strong> <?= $pedido['logradouro']; ?></p>
    <p><strong>Número:</strong> <?= $pedido['numero']; ?></p>
    <p><strong>Complemento:</strong> <?= $pedido['complemento']; ?></p>
    </div>

    <?php if ($result_itens->num_rows > 0): ?>
      <div class="itens-pedido">
      <h2>Itens do Pedido</h2>
        <?php while ($item = $result_itens->fetch_assoc()): ?>
          <div class="item-detalhe">
            <h3><?= $item['nome']; ?></h3>
            <p>Preço Unitário: R$ <?= number_format($item['preco'], 2, ',', '.'); ?></p>
            <p>Quantidade: <?= $item['quantidade']; ?></p>
            <p>Subtotal: R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>Não há itens neste pedido.</p>
    <?php endif; ?>
  </div>
  <?php include '../includes/footer.php'; ?>
    </div>
   


</body>


</html>