<?php
session_start();
include '../config/conexao.php';


// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}


// Atualiza o status do pedido se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id']) && isset($_POST['status'])) {
  $pedido_id = $_POST['pedido_id'];
  $status = $_POST['status'];


  // Atualiza o status do pedido
  $sql_update = "UPDATE pedidos SET status = ? WHERE id = ?";
  $stmt_update = $conn->prepare($sql_update);
  $stmt_update->bind_param("si", $status, $pedido_id);


  if ($stmt_update->execute()) {
    $msg = "Status do pedido #{$pedido_id} atualizado com sucesso!";
  } else {
    $erro = "Erro ao atualizar o status do pedido.";
  }
}


// Obtém todos os pedidos
$sql = "SELECT p.id, p.usuario_id, p.total, p.status, p.created_at, u.nome
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-BR">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pedidos - Fast Food</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  
  <link rel="stylesheet" href="../css/vendas.css">

</head>


<body>
<?php
  $paginaAtual = 'vendas';
  include '../includes/header.php'; ?>
  <div class="container">
  <div>
      <img class="imagem" src="../imgs/vendas2.png" alt="">
    </div>
    <div class="title">
    <h1>Gerenciar Vendas</h1>
    </div>


    <!-- Exibe mensagem de sucesso ou erro -->
    <?php if (isset($msg)): ?>
      <p style="color: green;"><?= $msg; ?></p>
    <?php elseif (isset($erro)): ?>
      <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>


    <?php if ($result->num_rows > 0): ?>
      <div class="lista-pedidos">
        <?php while ($pedido = $result->fetch_assoc()): ?>
          <div class="pedido-item">
            <div class="pedido-info">
              <h3>Pedido #<?= $pedido['id']; ?> - <?= $pedido['nome']; ?></h3>
              <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></p>
              <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></p>
              <p><strong>Status:</strong> <?= ucfirst($pedido['status']); ?></p>
            </div>
            <div class="pedido-acoes">
              <!-- Formulário para atualizar o status do pedido -->
              <form action="pedidos.php" method="POST">
                <input type="hidden" name="pedido_id" value="<?= $pedido['id']; ?>">
                <label for="status">Alterar Status:</label>
                <select name="status" required>
                  <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                  <option value="em-andamento" <?= $pedido['status'] == 'em-andamento' ? 'selected' : ''; ?>>Em andamento</option>
                  <option value="saiu-para-entrega" <?= $pedido['status'] == 'saiu-para-entrega' ? 'selected' : ''; ?>>Saiu para entrega</option>
                  <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                  <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
                <input class="botao" type="submit" value="Atualizar Status">
              </form>
              <a href="detalhes-pedido.php?id=<?= $pedido['id']; ?>" class="btn-detalhes">Ver Detalhes</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>Nenhum pedido foi encontrado.</p>
    <?php endif; ?>
  </div>


  <?php include '../includes/footer.php'; ?>


</body>


</html>